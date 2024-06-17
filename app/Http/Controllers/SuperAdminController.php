<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\CompanyUser;
use App\Models\Company;
use App\Models\Superadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    //Dashboard
    function dashboard()
    {
        return view('superadmin.dashboard');
    }

    public function manageAccount()
    {
        // Fetch profiles of all admins
        $admins = User::whereHas('companyUser', function ($query) {
            $query->where('isAdmin', true);
        })->with('profile')->get();

        // Pass the admins to the view
        return view('superadmin.manage-account', ['admins' => $admins]);
    }

    function profile_page()
    {
        $user = auth()->user();

        // Assuming you have a 'profile' relationship in your User model
        $profile = $user->profile;

        // Check if the user has a profile
        if ($profile) {
            // Pass the user and profile to the view
            return view('superadmin.profile-page', ['user' => $user, 'profile' => $profile]);
        }

    }

    function add_account()
    {
        // Fetch all companies to populate the dropdown
        $companies = Company::all();
        return view('superadmin.add-account', compact('companies'));
    }

    function add_accountPost(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'company' => 'required|exists:companies,CompanyID',
            'subscription_starts_at' => 'nullable|date', // Added by Alda for subscription
            'subscription_ends_at' => 'nullable|date', // Added by Alda for subscription

        ]);

        // Create the user account
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Create the profile account
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => $request->input('name'),
            // Add any additional profile fields if needed
        ]);

        // Link to the companyusers table
        $companyUser = CompanyUser::create([
            'UserID' => $user->id,
            'CompanyID' => $request->input('company'),
            'isAdmin' => 1,
            // Add any additional company user fields if needed
        ]);

        return redirect()->route('superadmin.manage_account')->with('success', 'Account created successfully.');
    }

    public function editAccount($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile;

        return view('superadmin.edit-account', compact('user', 'profile'));
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

        if ($request->filled('password')) {
            $user->update([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
        }else{
            $user->update([
                'email' => $request->input('email'),
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

        return redirect()->route('superadmin.manage_account')->with('success', 'Account updated successfully.');

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

        return redirect()->route('superadmin.manage_account')->with('success', 'Account deleted successfully.');
    }

    public function editCompany($id)
    {
        $company = Company::findOrFail($id);
        $industries = ['IT', 'Finance', 'Healthcare', 'Education', 'Manufacturing', 'Retail', 'Telecommunications', 'Transportation', 'Media and Entertainment', 'Hospitality', 'Real Estate', 'Construction'];


        return view('superadmin.edit-company', compact('company', 'industries'));
    }

    public function editCompanyPost(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string',
            'industry' => 'required|string',
            'address' => 'required|string',
            'website' => 'required|string',
            'sidebar_color' => 'required|string',
            'button_color' => 'required|string',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'subscription_starts_at' => 'nullable|date', // Added by Alda for subscription
            'subscription_ends_at' => 'nullable|date', // Added by Alda for subscription
        ]);

        //$company = Company::findOrFail($id);
        $company = Company::find($id);

        $company->update([
            'Name' => $request->input('name'),
            'Industry' => $request->input('industry'),
            'Address' => $request->input('address'),
            'Website' => $request->input('website'),
            'sidebar_color' => $request->input('sidebar_color'),
            'button_color' => $request->input('button_color'),
            'subscription_starts_at' => $request->input('subscription_starts_at'), // Added by Alda for subscription
            'subscription_ends_at' => $request->input('subscription_ends_at'), // Added by Alda for subscription

        ]);

        if ($request->hasFile('logo')) {
            $companyLogo = $request->file('logo');
            $companyLogoPath = $companyLogo->storeAs('CompanyLogos', $companyLogo->getClientOriginalName(), 'public');
            $company->update(['company_logo' => $companyLogoPath]);
        }
        
       return redirect()->route('superadmin.manage_company')->with('success', 'Company updated successfully.');
    }

    function add_company(){
        return view('superadmin.add-company');
    }

    function add_companyPost(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string',
            'industry' => 'required|string',
            'address' => 'required|string',
            'website' => 'required|string',
            'sidebar_color' => 'required|string',
            'button_color' => 'required|string',
            'subscription_starts_at' => 'nullable|date', // Added by Alda for subscription
            'subscription_ends_at' => 'nullable|date', // Added by Alda for subscription

        ]);

        $companyLogo = $request->file('logo');
        $companyLogoPath = $companyLogo->storeAs('CompanyLogos', $companyLogo->getClientOriginalName(), 'public');
    
        // Create the company with or without the logo path
        $company = Company::create([
            'Name' => $request->input('name'),
            'Industry' => $request->input('industry'),
            'Address' => $request->input('address'),
            'Website' => $request->input('website'),
            'sidebar_color' => $request->input('sidebar_color'),
            'button_color' => $request->input('button_color'),
            'subscription_starts_at' => $request->input('subscription_starts_at'), // Added by Alda for subscription
            'subscription_ends_at' => $request->input('subscription_ends_at'), // Added by Alda for subscription

        ]);

          //update company_logo field to be companyLogoPath
          $company->update(['company_logo' => $companyLogoPath]);
    
        return redirect()->route('superadmin.manage_company')->with('success', 'Company created successfully.');
    }
    
    public function manageCompany()
    {
        // Fetch all companies from the database
        $companies = Company::all();

        // Pass the companies to the view
        return view('superadmin.manage-company', ['companies' => $companies]);
        
    }

    public function deleteCompany($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return redirect()->route('superadmin.manage_company')->with('error', 'Company not found.');
        }

        // Delete the company
        $company->delete();

        return redirect()->route('superadmin.manage_company')->with('success', 'Company deleted successfully.');
    }


}
