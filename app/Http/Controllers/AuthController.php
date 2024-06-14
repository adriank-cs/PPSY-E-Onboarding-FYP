<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller {
    
    function login() {
        if (Auth::check()) {
            $user = Auth::user();
    
            if ($user->isSuperadmin()) {
                return redirect()->route('superadmin.profile_page');
            } elseif ($user->companyUser && $user->companyUser->isAdmin) {
                return redirect()->route('admin.profile_page');
            } else {
                return redirect()->route('employee.profile_page');
            }
        }
    
        return view('login');
    }

    public function loginPost(Request $request){
    // Validate login request data
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Get credentials from request
    $credentials = $request->only('email', 'password');

    // Attempt to login
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $currentDate = now()->format('Y-m-d');
        $lastLoginDate = $user->last_login_date;

        // Check if the last login was yesterday
        if ($lastLoginDate === now()->subDay()->format('Y-m-d')) {
            // Increment the login streak
            $user->login_streak += 1;
        } else if ($lastLoginDate !== $currentDate) {
            // Reset the login streak if the last login was not yesterday and not today
            $user->login_streak = 1;
        }

         // Update the last login date to today
         $user->last_login_date = $currentDate;
         $user->save();

        // Check if the user is a superadmin
        if ($user->superadmin) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->companyUser && $user->companyUser->isAdmin) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    }

     return redirect()->route('login')->with('error', 'Invalid Credentials');
}

    function logout() {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }

}

