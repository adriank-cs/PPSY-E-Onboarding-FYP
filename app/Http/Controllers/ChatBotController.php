<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\LaravelCache;

use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

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
            $bot->reply('Hey brotha!');
        });

        $botman->hears('Retrieve Document', function (BotMan $bot) {

            //Create attachment
            $url = Storage::url('pdf_attachments/ih7pxrY3KI6G0oscNbWLzcNT11h2njxqslIr4HxG.pdf');

            //Build message object
            $message = OutgoingMessage::create("File: " . $url);

            Log::info(print_r($message, true));

            //Reply message
            $bot->reply("Here's the file you requested!");
            $bot->reply($message);
        });

        $botman->hears('Navigate to Page', function (BotMan $bot) {

            //Create attachmentURL
            $url = route('employee.profile_page', [], false);

            //Build message object
            $message = OutgoingMessage::create("Link: " . $url);

            Log::info(print_r($message, true));

            //Reply message
            $bot->reply("Here's the page you requested!");
            $bot->reply($message);
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

     //ChatBot Commands

}
