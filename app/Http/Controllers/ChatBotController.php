<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

//To retrieve document
use App\Models\Item;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\LaravelCache;

use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Botman\BotMan\Messages\Incoming\Answer;


class ChatBotController extends Controller
{
    public $config = [

        /*
        |--------------------------------------------------------------------------
        | Conversation Cache Time
        |--------------------------------------------------------------------------
        |
        | BotMan caches each started conversation. This value defines the
        | number of minutes that a conversation will remain stored in
        | the cache.
        |
        */
        'conversation_cache_time' => 40,
    
        /*
        |--------------------------------------------------------------------------
        | User Cache Time
        |--------------------------------------------------------------------------
        |
        | BotMan caches user information of the incoming messages.
        | This value defines the number of minutes that this
        | data will remain stored in the cache.
        |
        */
        'user_cache_time' => 30,
    ];


    public function handle()
    {

        //Assign user property first
        $user = auth()->user();

        //Load driver
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create($this->config, new LaravelCache());

        $botman->hears('Hi', function (BotMan $bot) use ($user) {
            $bot->reply('Hey '. $user->name . ', I hope you are doing well! How can I assist you today?');
        });

        $botman->hears('Hello', function (BotMan $bot) use ($user) {
            $bot->reply('Hello ' . $user->name . ', I hope you are doing well! How can I assist you today?');
        });

        $botman->hears('Hey', function (BotMan $bot) use ($user) {
            $bot->reply('Hey ' . $user->name . ', I hope you are doing well! How can I assist you today?');
        });

        $botman->hears('Who is the project manager?', function (BotMan $bot) use ($user) {
            $bot->reply('Interesting question, ' . $user->name . '! The project manager of this e-onboarding system is Mr. Kent from People Psyence.');
        });

        /////////////////////////
        //HELP COMMAND FOR USER//
        /////////////////////////
        $botman->hears('Help', function (BotMan $bot) {
            $bot->reply('Here is a list of commands I understand: <br>1. Navigate to Page<br>2. Retrieve Document');
        });

        //////////////////
        //DIRECT PROMPTS//
        //////////////////

        //Navigate to page
        $botman->hears('Navigate to Page', function (BotMan $bot) {
            $bot->startConversation(new NavigationConversation());
        });

        //Retrieve document
        $botman->hears('Retrieve Document', function (BotMan $bot) {
            $bot->startConversation(new FileRetrieveConversation());
        });

        //Default fallback
        $botman->fallback(function($bot) {
            $bot->reply('Sorry, I did not understand your commands.<br><br>Type "help" to see the list of commands.');
        });

        $botman->listen();
    }

    public function frame() {
        return view('includes.chatbot-frame');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
}

class NavigationConversation extends Conversation {

    //Current user
    protected $user;

    //Page keyword
    protected $keyword;

    //All available employee routes for Chatbot
    protected $employeeRoutes;

    //All available admin routes for Chatbot
    protected $adminRoutes;

    //All available super admin routes for Chatbot
    protected $superadminRoutes;

    public function askPage() {
        $this->ask('Which page would you like to navigate to?', function (Answer $answer) {
            //If keyword is empty
            if ($answer->getText() == "") {
                $this->say('Please provide me with a keyword to navigate to a page.');

                return true;
            }
            
            //Get keyword
            $this->keyword = strtolower($answer->getText());

            //Local routes
            $routes = collect([]);

            //Check user type
            if ($this->user->isEmployee()) {
                $routes = $this->employeeRoutes;
            } else if ($this->user->isAdmin()) {
                $routes = $this->adminRoutes;
            } else if ($this->user->isSuperadmin()) {
                $routes = $this->superadminRoutes;
            }

            //Default URL
            $url = "";
            $urlKey = "";

            foreach ($routes as $key => $value) { 
                if (str_contains($key, $this->keyword)) {
                    $url = $value;
                    $urlKey = $key;
                    break;
                }
            }

            if ($url == "") {
                $this->say('Sorry, I could not find the page you requested. Please try again! (' . $this->keyword . ")");

                $similarKeys = collect([]);

                foreach ($routes as $key => $value) {
                    $smText = similar_text($key, $this->keyword, $percent);

                    if ($percent > 50) {
                        $similarKeys->push($key);
                    }
                }

                if ($similarKeys->count() > 0) {
                    $this->say('Did you mean: ' . $similarKeys->implode(', '));
                }

                return true;
            }
            else {
                //Reply with requested link
                $this->say('Here is the page you requested! (' . $urlKey . ")");
                $this->say("Link: " . $url);

                //Stop conversation
                return true;
            }

        });
    }

    public function run()
    {
        //Assign user property first
        $this->user = auth()->user();

        //All available employee routes for Chatbot
        $this->employeeRoutes = collect([
            //Dashboard
            "dashboard" => route('employee.dashboard', [], false),

            //Profile
            "profile" => route('employee.profile_page', [], false),

            //Modules
            "modules" => route('employee.my_modules', [], false),
            "onboarding" => route('employee.my_modules', [], false),

            //Discussion
            "discussion" => route('employee.discussion', [], false),
            "forum" => route('employee.discussion', [], false),
            "post" => route('employee.discussion', [], false),
            "questions" => route('employee.check-post', [], false),

            //Colleagues 
            "colleague" => route('employee.find_colleagues', [], false),
            "employee" => route('employee.find_colleagues', [], false),

            //Leaderboard
            "leaderboard" => route('employee.leaderboard', [], false),
            "login streak" => route('employee.leaderboard', [], false),
        ]);

        //All available admin routes for Chatbot
        $this->adminRoutes = collect([
            //Dashboard
            "dashboard" => route('admin.dashboard', [], false),

            //Profile
            "profile" => route('admin.profile_page', [], false),

            //Progress Tracking
            "progress tracking" => route('admin.progress-tracking', [], false),
            "track progress" => route('admin.progress-tracking', [], false),

            //Manage Accounts
            "manage users" => route('manage_account', [], false),
            "manage accounts" => route('manage_account', [], false),
            "create users" => route('add_account', [], false),
            "add users" => route('add_account', [], false),
            "create accounts" => route('add_account', [], false),
            "add accounts" => route('add_account', [], false),

            //Manage Modules
            "manage modules" => route('admin.manage_modules', [], false),
            "create modules" => route('admin.add_module', [], false),

            //Discussion
            "discussion" => route('randomPost', [], false),
            "forum" => route('randomPost', [], false),

            //Colleagues 
            "colleague" => route('admin.find_colleagues', [], false),
            "employee" => route('admin.find_colleagues', [], false),

            //Leaderboard
            "leaderboard" => route('admin.leaderboard', [], false),
            "login streak" => route('admin.leaderboard', [], false),
        ]);

        //All available super admin routes for Chatbot
        $this->superadminRoutes = collect([
            //Dashboard
            "dashboard" => route('superadmin.dashboard', [], false),

            //Profile
            "profile" => route('superadmin.profile_page', [], false),

            //Manage Accounts
            "manage users" => route('superadmin.manage_account', [], false),
            "manage accounts" => route('superadmin.manage_account', [], false),
            "create users" => route('superadmin.add_account', [], false),
            "add users" => route('superadmin.add_account', [], false),
            "create accounts" => route('superadmin.add_account', [], false),
            "add accounts" => route('superadmin.add_account', [], false),

            //Manage Company
            "manage company" => route('superadmin.manage_company', [], false),
            "manage companies" => route('superadmin.manage_company', [], false),
            "create company" => route('superadmin.add_company', [], false),
            "add company" => route('superadmin.add_company', [], false),
            "create companies" => route('superadmin.add_company', [], false),
        ]);

        $this->askPage();
    }
        
}

class FileRetrieveConversation extends Conversation {

        //Current user
        protected $user;

        //Page keyword
        protected $keyword;

        public function askDocument() {

            $this->ask('Which document would you like retrieve?', function (Answer $answer) {
                //If keyword is empty
                if ($answer->getText() == "") {
                    $this->say('Please provide me with a keyword for a document to retrieve.');
    
                    return true;
                }
                
                //Get keyword
                $this->keyword = strtolower($answer->getText());
    
                //Default URL
                $pdfURL = "";
                $pdfName = "";

                //Get all attachments of the user's company
                $items = Item::whereRelation('chapter.module.company', 'CompanyID', $this->user->companyUser->CompanyID)->select('pdf_attachments')->get();
    
                //Loop through pdf attachment rows
                foreach ($items as $item) {
                    //Loop through each individual document
                    $pdfs = json_decode($item->pdf_attachments, true);

                    if (!empty($pdfs)|| $pdfs != []) {

                        //Only loop if there are documents
                        foreach ($pdfs as $pdf) {

                            $smText = similar_text($pdf['name'], $this->keyword, $percent);

                            //Find similar documents
                            if ($percent > 60) {
                                $pdfURL = $pdf['url'];
                                $pdfName = $pdf['name'];

                                break;
                            }

                        }
                    }

                }
    
                if ($pdfURL == "") {
                    $this->say('Sorry, I could not find the document you requested. Please try again! (' . $this->keyword . ")");
    
                    $similarKeys = collect([]);

                    //Loop through pdf attachment rows
                    foreach ($items as $item) {
                        //Loop through each individual document
                        $pdfs = json_decode($item->pdf_attachments, true);

                        if (!empty($pdfs)|| $pdfs != []) {
                            //Only loop if there are documents
                            foreach ($pdfs as $pdf) {

                                Log::info("Checking similar PDFs");

                                $smText = similar_text($pdf['name'], $this->keyword, $percent);

                                //Find similar documents
                                if ($percent > 25) {
                                    $similarKeys->push($pdf['name']);
                                }

                            }
                        }

                        //Capped at 5 similar documents
                        if ($similarKeys->count() >= 5) {
                            break;
                        }
                    }
    
                    if ($similarKeys->count() > 0) {
                        $this->say('Did you mean: ' . $similarKeys->implode(', '));
                    }
    
                    return true;
                }
                else {
                    //Reply with requested link
                    $this->say('Here is the file you requested! (' . $pdfName . ")");
                    $this->say("[" . $pdfName . "] " . "File: " . $pdfURL);

                    //Stop conversation
                    return true;
                }
    
            });

        }

        public function run() {
            //Assign user property first
            $this->user = auth()->user();

            //Run function
            $this->askDocument();
        }

}
