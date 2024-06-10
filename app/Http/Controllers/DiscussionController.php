<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DiscussionController extends Controller {

    function homepage() {
        //$buttonColor = $company->button_color;
        //dd($buttonColor);
        return view('admin.discussion');
    }

    function searched() {
        // $buttonColor = $company->button_color;
        return view('discussion.searched');
    }

    function typeown() {
        // $buttonColor = $company->button_color;
        return view('discussion.typeown');
    }

    

}

