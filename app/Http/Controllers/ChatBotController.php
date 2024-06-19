<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        //Load driver
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $botman = BotManFactory::create($this->config, new LaravelCache());

        $botman->hears('Hi', function (BotMan $bot) {
            $bot->reply('Hey, I hope you are doing well! How can I assist you today?');
        });

        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hello, I hope you are doing well! How can I assist you today?');
        });

        $botman->hears('Retrieve Document', function (BotMan $bot) {

            //Create attachment
            $url = Storage::url('pdf_attachments/ih7pxrY3KI6G0oscNbWLzcNT11h2njxqslIr4HxG.pdf');
            $filename = 'test.pdf';

            //Build message object
            $message = OutgoingMessage::create("[" . $filename . "] " . "File: " . $url);

            //Reply message
            $bot->reply("Here's the file you requested!");
            $bot->reply($message);
        });

        // $botman->hears('Navigate to XXX', function (BotMan $bot) {

        //     //Create attachmentURL
        //     $url = route('employee.profile_page', [], false);

        //     //Build message object
        //     $message = OutgoingMessage::create("Link: " . $url);

        //     //Reply message
        //     $bot->reply("Here's the page you requested!");
        //     $bot->reply($message);
        // });

        //HELP COMMAND FOR USER
        $botman->hears('Help', function (BotMan $bot) {
            $bot->reply('Here is a list of commands I understand: <br>1. Navigate to Page<br>2. Retrieve Document');
        });

        //DIRECT PROMPTS
        $botman->hears('Navigate to Page', function (BotMan $bot) {
            $bot->startConversation(new NavigationConversation());
        });

        $botman->fallback(function($bot) {
            $bot->reply('Sorry, I did not understand your commands.<br><br>Here is a list of commands I understand: <br>1. Navigate to Page<br>2. Retrieve Document');
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

// class FileRetrieveConversation extends Conversation {

//         //Current user
//         protected $user;

//         //Page keyword
//         protected $keyword;

//         public function askDocument() {

//         }

// }
