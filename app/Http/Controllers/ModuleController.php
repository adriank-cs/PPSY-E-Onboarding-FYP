<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage for image handling
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CompanyUser;
use App\Models\UserResponse;
use App\Models\ModuleQuestion;
use App\Http\Requests\StoreModuleRequest;

class ModuleController extends Controller
{

    public function modules()
    {
        $modules = Module::all();
        return view('employee.onboarding-home-page', compact('modules')); // Return the view
    }


    public function create()
    {
        return view('admin.create-modules'); // Return the view
    }

    function manage_modules()
    {
        $adminUser = auth()->user();

        // Fetch modules belonging to the company ID of the currently logged-in admin
        $modules = Module::where('CompanyID', $adminUser->companyUser->CompanyID)->get();

        // Pass the profiles to the view
        return view('admin.manage-modules', ['modules' => $modules]);

    }

    function add_module()
    {
        return view('admin.add-modules');
    }

    public function add_modulePost(Request $request)
    {
        $adminUser = auth()->user();
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        // Store the uploaded image
        $fileName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('modules', $fileName, 'public');
        $path = 'modules/'. $fileName;
        
        // Insert the record into the database
        Module::create([
            'title' => $request->title,
            'image_path' => $path,
            'CompanyID' => $adminUser->companyUser->CompanyID,
        ]);
    
        // Redirect back with success message
        return redirect()->route('admin.manage_modules')->with('success', 'Module created successfully!');
    }

    public function configureModule($id){

        $moduleId = $id;

        // Fetch modules belonging to the company ID of the currently logged-in admin
        $chapters = Chapter::where('module_id', $moduleId)->get();

        // Pass the profiles to the view
        return view('admin.configure-module', ['chapters' => $chapters, 'moduleId' => $moduleId]);

    }

    public function add_chapter($moduleId)
    {
        return view('admin.add-chapters', compact('moduleId'));
    }
    
    public function add_chapterPost(Request $request, $moduleId)
    {
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
    
        // Create and save the chapter
        Chapter::create([
            'title' => $request->title,
            'module_id' => $moduleId,
        ]);
    
        // Redirect back to the configure module page
        return redirect()->route('admin.configure_module', ['id' => $moduleId])
                         ->with('success', 'Chapter added successfully!');
    }

    // public function editModulePost(Request $request, $id)
    // {

    //     // Validate the form data
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'profilePicture' => 'image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    

    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $imagePath = $image->storeAs('modules', $image->getClientOriginalName(), 'public');

    //         $module->update(['image_path' => $imagePath]);

    //     }


    //     return redirect()->route('manage_account')->with('success', 'Account updated successfully.');

    // }




    // public function show(Module $module)
    // {
    //     $user = auth()->user(); // Assuming you have a user authentication system
    //     if (auth()->check()) {

    //         return view('employee.module-details', compact('module', 'completionPercentage', 'userResponses'));
    //     } else {
    //         // Redirect to login or handle unauthenticated user scenario
    //         return redirect()->route('login');
    //     }
    // }


}



