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
                 return redirect()->route('login')->with('error', 'Invalid User');
            }

            Auth::login($user);

            if (Auth::check()) {
                $user = Auth::user();
                if ($user->isSuperadmin()) {
                    return redirect()->route('superadmin.profile_page');
                } elseif ($user->companyUser && $user->companyUser->isAdmin){
                    return redirect()->route('admin.profile_page');
                } else {
                    return redirect()->route('employee.profile_page');
                }
            }
          

        } catch(\Throwable $th){
            dd('something went wrong!'.$th->getMessage());
        }
    }
}
