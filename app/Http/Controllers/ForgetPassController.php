<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\CustomResetPassword;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ForgetPassController extends Controller
{
    function forgotpassword_page(){
        return view('forgotpassword-page');
    }

    function email_notify_page(Request $request){
        try{
            $request->validate(['email' => 'required|email']);
            $user = User::where('email', $request->email)->first();

            //If user not found 
            if (!$user) {
                return redirect()->route('forgotpassword_page')->with('error', 'Enter valid E-mail.');     
            } 

            //generate random token
            $token = Str::random(64); 

            //Check if an entry for the email already exists in password_resets
            $existingRecord = DB::table('password_resets')
                ->where('email', $request->email)->first();
            
            if($existingRecord) {
                // Update the existing record
                DB::table('password_resets')->where('email', $request->email)->update([
                        'token' => $token,
                        'created_at' => Carbon::now()
                ]);
            } else {
                // Insert a new record
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                    ]);
            }

            $resetLink = route('reset_password_page', ['token' => $token]);
            Mail::to($request->email)->send(new CustomResetPassword($resetLink));
            return view('emailnotification-page');

        }catch(\Throwable $th){
            dd('something went wrong!'.$th->getMessage());
            //return redirect()->route('forgotpassword_page')->with('error', 'Enter E-mail in correct format.'); 
        }
        
    }

    function reset_password_page($token){
        return view('email-template',['token' => $token]);
    }

    function reset_password(Request $request){
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);

            $updatePassword = DB::table('password_resets')->where([
                    'email' => $request->email, //check if the email is valid
                    'token' => $request->token 
                ])->first();
            
            if(!$updatePassword) {
                return redirect()->route('reset_password_page', ['token' => $request->token])->with('error', 'Invalid Email or Password Format!');
            }

            User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            //Delete the token after password reset
            DB::table('password_resets')->where(['email'=> $request->email])->delete();
            
            return redirect()->route('login', ['token' => $request->token])->with('success', 'Your password has been changed!');

        }catch(\Throwable $th){
            return redirect()->route('reset_password_page', ['token' => $request->token])->with('error', 'Invalid Email or Password Format!');
        }
    }
    
}
