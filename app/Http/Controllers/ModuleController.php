<?php

namespace App\Http\Controllers;

use App\Models\Item;
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

    public function editModule($id)
    {
        $module = Module::find($id);
        return view('admin.edit-modules', compact('module'));
    }

    public function editModulePost(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        $module = Module::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->storeAs('modules', $image->getClientOriginalName(), 'public');

            $module->update(['image_path' => $imagePath]);

        }

        $module->update([
            'title' => $request->title,
        ]);

        return redirect()->route('admin.manage_modules')->with('success', 'Module updated successfully.');
    }

    public function deleteModule($id)
    {
        $module = Module::find($id);
        $module->delete();

        return redirect()->route('admin.manage_modules')->with('success', 'Module deleted successfully.');
    }
    public function manageChapter($id){

        $moduleId = $id;

        // Fetch modules belonging to the company ID of the currently logged-in admin
        $chapters = Chapter::where('module_id', $moduleId)->get();

        // Pass the profiles to the view
        return view('admin.manage-chapters', ['chapters' => $chapters, 'moduleId' => $moduleId]);

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
            'description' => 'required|string',
        ]);
    
        // Create and save the chapter
        Chapter::create([
            'title' => $request->title,
            'module_id' => $moduleId,
            'description' => $request->description,
        ]);
    
        // Redirect back to the configure module page
        return redirect()->route('admin.manage_chapter', ['id' => $moduleId])
                         ->with('success', 'Chapter added successfully!');
    }

    public function editChapter($id)
    {
        $chapter = Chapter::find($id);
        return view('admin.edit-chapter', compact('chapter'));
    }

    public function editChapterPost(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $chapter = Chapter::find($id);

        $chapter->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.manage_chapter', ['id' => $chapter->module_id])->with('success', 'Chapter updated successfully.');
    }

    public function deleteChapter($id)
    {
        $chapter = Chapter::find($id);
        $moduleId = $chapter->module_id;
        $chapter->delete();

        return redirect()->route('admin.manage_chapter', ['id' => $moduleId])->with('success', 'Chapter deleted successfully.');
    }

    public function managePage($id){

        $chapterId = $id;

        // Fetch modules belonging to the company ID of the currently logged-in admin
        $pages = Item::where('chapter_id', $chapterId)->get();

        // Pass the profiles to the view
        return view('admin.manage-pages', ['pages' => $pages, 'chapterId' => $chapterId]);

    }

    public function add_page($chapterId)
    {
        return view('admin.add-pages', compact('chapterId'));
    }
    
    public function add_pagePost(Request $request, $chapterId)
    {
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'due_date' => 'required|date',
        ]);
    
        // Create and save the chapter
        Item::create([
            'title' => $request->title,
            'chapter_id' => $chapterId,
            'description' => $request->description,
            'content' => $request->content,
            'due_date' => $request->due_date,
        ]);
    
        // Redirect back to the configure module page
        return redirect()->route('admin.manage_page', ['id' => $chapterId])
                         ->with('success', 'Page added successfully!');
    }

    public function editPage($id)
    {
        $page = Item::find($id);
        return view('admin.edit-page', compact('page'));
    }

    public function editPagePost(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'due_date' => 'required|date',
        ]);

        $page = Item::find($id);

        $page->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.manage_page', ['id' => $page->chapter_id])->with('success', 'Page updated successfully.');
    }

    public function deletePage($id)
    {
        $page = Item::find($id);
        $chapterId = $page->chapter_id;
        $page->delete();

        return redirect()->route('admin.manage_page', ['id' => $chapterId])->with('success', 'Page deleted successfully.');
    }





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



