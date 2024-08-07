<?php

namespace App\Http\Controllers;

use App\Models\ModuleQuestion;
use App\Models\Profile;
use App\Models\User;
use App\Models\CompanyUser;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    //Dashboard
    function dashboard()
    {
        return view('admin.dashboard');
    }

    //Progress Tracking
    function progressTracking()
    {
        return view('admin.progress-tracking');
    }

    function manage_account()
    {
        $user = Auth::user();

        // Fetch profiles belonging to the company ID of the currently logged-in admin
        $companyId = $user->companyUser->CompanyID;
        $profiles = Profile::join('companyusers', 'profiles.user_id', '=', 'companyusers.UserID')
            ->where('companyusers.CompanyID', '=', $companyId)
            ->get();

        // Pass the profiles to the view
        return view('admin.manage-account', ['profiles' => $profiles]);

    }

    function add_account()
    {
        return view('admin.add-account');
    }

    function add_accountPost(Request $request)
    {
        // Validate form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'profilePicture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Create a new user record
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);


        // Get the current authenticated user (assuming the admin is logged in)
        $adminUser = auth()->user();

        //Create a new company user record
        CompanyUser::create([
            'UserID' => $user->id,
            'CompanyID' => $adminUser->companyUser->CompanyID,
        ]);

        if ($request->hasFile('profilePicture')) {
            $profilePicture = $request->file('profilePicture');
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePicture->getClientOriginalName(), 'public');

            $user->profile()->create([
                'user_id' => $user->id,
                'employee_id' => $request->input('employeeID'),
                'name' => $request->input('name'),
                'gender' => $request->input('gender'),
                'dob' => $request->input('dob'),
                'age' => $request->input('age'),
                'position' => $request->input('position'),
                'dept' => $request->input('dept'),
                'bio' => $request->input('bio'),
                'phone_no' => $request->input('phoneNo'),
                'address' => $request->input('address'),
            ]);

            $user->profile()->update(['profile_picture' => $profilePicturePath]);

        }

        // Update the isAdmin status in the companyusers table
        if ($request->has('isAdmin')) {
            $user->companyUser()->update([
                'isAdmin' => $request->input('isAdmin') ? true : false,
            ]);
        } else {
            // If 'isAdmin' is not present in the request, ensure to set it to false
            $user->companyUser()->update([
                'isAdmin' => false,
            ]);
        }

        return redirect()->route('manage_account')->with('success', 'Account added successfully.');
    }

    function profile_page()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch the profile data for the logged-in user
        $admin = Profile::where('user_id', $user->id)->first();

        // Check if the user has a company user record
        if ($user->companyUser) {
            // Get the company ID from the user's company user record
            $companyId = $user->companyUser->CompanyID;

            // Fetch profiles belonging to the specified company ID
            $profiles = Profile::join('companyusers', 'profiles.user_id', '=', 'companyusers.UserID')
                ->where('companyusers.CompanyID', '=', $companyId)
                ->get();

            // Pass the profiles to the view
            return view('admin.profile-page', compact('user', 'admin', 'profiles'));
        }

        // Handle the case when the user doesn't have a company user record
        return redirect()->route('login')->with('error', 'User does not have a company association.');
    }

    public function updateProfile(Request $request)
    {
        // Validate the form data
        $request->validate([
            'phone' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'bio' => 'required|string'
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Fetch the profile data for the logged-in user
        $admin = Profile::where('user_id', $user->id)->first();

        // Update the profile fields
        $admin->update([
            'phone_no' => $request->input('phone'),
            'dob' => $request->input('dob'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'bio' => $request->input('bio'),
        ]);

        // Update the user's email
        $user->update([
            'email' => $request->input('email'),
        ]);

        // Redirect back to the profile page with a success message
        return redirect()->route('admin.profile_page')->with('success', 'Profile updated successfully.');
    }

    public function editAccount($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile;

        return view('admin.edit-account', compact('user', 'profile'));
    }

    public function editAccountPost(Request $request, $id)
    {

        // Validate the form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'profilePicture' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::find($id);

        $user->update([
            'email' => $request->input('email'),
        ]);

        //check if have password
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);
        }

        // Update the user's profile details
        $user->profile->update([
            'employee_id' => $request->input('employeeID'),
            'dept' => $request->input('dept'),
            'phone_no' => $request->input('phoneNo'),
            'dob' => $request->input('dob'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'age' => $request->input('age'),
            'bio' => $request->input('bio'),
        ]);

        if ($request->hasFile('profilePicture')) {
            $profilePicture = $request->file('profilePicture');
            $profilePicturePath = $profilePicture->storeAs('profile_pictures', $profilePicture->getClientOriginalName(), 'public');

            $user->profile()->update(['profile_picture' => $profilePicturePath]);

        }

        // Update the isAdmin status in the companyusers table
        if ($request->has('isAdmin')) {
            $user->companyUser()->update([
                'isAdmin' => $request->input('isAdmin') ? true : false,
            ]);
        } else {
            // If 'isAdmin' is not present in the request, ensure to set it to false
            $user->companyUser()->update([
                'isAdmin' => false,
            ]);
        }

        return redirect()->route('manage_account')->with('success', 'Account updated successfully.');

    }

    public function deleteAccount($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('manage_account')->with('error', 'User not found.');
        }

        // Delete the associated profile
        $user->profile()->delete();

        // Delete the associated company user
        $user->companyUser()->delete();

        // Delete the user
        $user->delete();

        return redirect()->route('manage_account')->with('success', 'Account deleted successfully.');
    }

    public function uploadImage(Request $request){

        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->storeAs('TinyMceImages', $fileName, 'public');
        $path = 'storage/TinyMceImages/' . $fileName;

        // Manually construct the full URL
        $fullUrl = config('app.url') . '/' . $path;

        return response()->json(['location' => $fullUrl]);
    }


    public function findColleagues()
    {
        $companyId = auth()->user()->companyUser->CompanyID;

        // Fetch and sort users
        $adminUsers = User::join('companyusers', 'users.id', '=', 'companyusers.UserID')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('companyusers.CompanyID', '=', $companyId)
            ->where('companyusers.isAdmin', '=', 1)
            ->select('users.*', 'profiles.*')
            ->get();

        $nonAdminUsers = User::join('companyusers', 'users.id', '=', 'companyusers.UserID')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('companyusers.CompanyID', '=', $companyId)
            ->where('companyusers.isAdmin', '=', 0)
            ->select('users.*', 'profiles.*')
            ->get();

        return view('admin.find-colleagues', compact('adminUsers', 'nonAdminUsers'));
    }

    public function colleagueDetails($id)
    {
        $colleague = User::with('profile')->findOrFail($id);
        return view('admin.colleague-details', compact('colleague'));
    }

    public function leaderboard()
    {
        $companyId = Auth::user()->companyUser->CompanyID;

    // Get users of the same company, ordered by login streak in descending order, with profiles eager loaded
    $users = User::with('profile')->whereHas('companyUser', function ($query) use ($companyId) {
        $query->where('CompanyID', $companyId);
    })->orderBy('login_streak', 'desc')->get();

    // Find the current user's rank
    $rank = $users->search(function($user) {
        return $user->id === Auth::id();
    }) + 1;

    return view('admin.leaderboard', compact('users', 'rank'));
    }
}


