<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // for google auth
    public function redirect(){
        return Socialite::driver('google')->redirect();
    } 

    // for login with google
    public function callback_google(){
        try{
            //check if database include the user google email or not 
            $google_user = Socialite::driver('google')->user();

            //$user = User::where('google_id',$google_user->getId())->first(); //check the user by using google id
            $user = User::where('email',$google_user->getEmail())->first();
            //if user not inside the database display error message 
            if (!$user) {
               // echo "<script>alert('Invalid user');</script>";
                return redirect()->route('login_page')->with('error', 'Invalid User');
            }
            
            //if the google acc already in database can direct to the progile page
            Auth::login($user);
            return redirect()->intended('/employee/profile-page');
            

        } catch(\Throwable $th){
            dd('something went wrong!'.$th->getMessage());
        }
    }
}
