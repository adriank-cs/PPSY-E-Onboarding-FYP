<?php 

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Profile;
use App\Models\Item;
use App\Models\ItemProgress;
use App\Models\ChapterProgress;
use App\Models\Chapter;
use App\Models\Module;
use App\Models\AssignedModule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class EmployeeController extends Controller {


    function sidebar()
    {
        return view('includes.sidebar-employee'); 
    }

    //Dashboard
    function dashboard()
    {
        return view('employee.dashboard'); 
    }

    function profile_page() {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch the profile data for the logged-in user
        $employee = Profile::where('user_id', $user->id)->first();

        // Check if the user has a company user record
        if ($user->companyUser) {
            // Get the company ID from the user's company user record
            $companyId = $user->companyUser->CompanyID;

            $company = Company::find($companyId);
           
            // Fetch profiles belonging to the specified company ID
            $profiles = Profile::join('companyusers', 'profiles.user_id', '=', 'companyusers.UserID')
                ->where('companyusers.CompanyID', '=', $companyId)
                ->get();

            // Pass the profiles to the view
            return view('employee.profile-page', compact('user', 'employee', 'profiles'));  
        }

        // Handle the case when the user doesn't have a company user record
        return redirect()->route('login')->with('error', 'User does not have a company association.');
    }

    public function updateProfile(Request $request){

        $request->validate([
                    'phone' => 'required|string',
                    'dob' => 'required|date',
                    'gender' => 'required|string',
                    'email' => 'required|email',
                    'address' => 'required|string',
                    'bio' => 'required|string',
                ]);


        $user = auth()->user();

        // Fetch the profile data for the logged-in user
        $employee = Profile::where('user_id', $user->id)->first();

        // Update the profile fields
        $employee->update([
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

        return redirect()->route('employee.profile_page')->with('success', 'Profile updated successfully.');
    }



    public function generateDynamicCss($companyId){
        $company = Company::find($companyId);
        $css = View::make('css.colors', compact('company'))->render();
        file_put_contents(public_path('css/colors.css'), $css);
    }

    public function showMyModules(){
        $user = auth()->user();

         // Get all assigned modules for the authenticated user
        $assignedModules = AssignedModule::where('UserID', $user->id)->get();

        // Extract module IDs from the assigned modules
        $moduleIds = $assignedModules->pluck('ModuleID');

        // Get all modules that match the module IDs
        $modules = Module::whereIn('id', $moduleIds)->get();

        // Return the modules to a view
        return view('employee.my-modules', compact('modules'));
    }


    public function checkItemProgress($moduleId)
{
    $user = auth()->user();
    $companyId = $user->companyUser->CompanyID;
    $module = Module::findOrFail($moduleId);

    // Check if the module is assigned to the user
    $assignedModule = AssignedModule::where('UserID', $user->id)
                                    ->where('ModuleID', $moduleId)
                                    ->firstOrFail();

    // Ensure all item progress records exist for the user
    $this->ensureAllItemProgressRecordsExist($user->id, $companyId, $moduleId);

    // Fetch the latest incomplete item
    $latestItemProgress = \DB::table('item_progress')
        ->join('item', 'item_progress.ItemID', '=', 'item.id')
        ->join('chapters', 'item.chapter_id', '=', 'chapters.id')
        ->where('item_progress.UserID', $user->id)
        ->where('item_progress.ModuleID', $moduleId)
        ->where('item_progress.CompanyID', $companyId)
        ->whereNull('item_progress.IsCompleted')
        ->orderBy('chapters.id') // prioritize chapters
        ->orderBy('item_progress.order') // prioritize items by order within chapters
        ->first();

    if ($latestItemProgress) {
        return redirect()->route('employee.view_page', ['itemId' => $latestItemProgress->ItemID]);
    } else {
        // Get the first chapter and first item of the module
        $firstChapter = Chapter::where('module_id', $moduleId)->orderBy('id')->first();
        $firstItem = Item::where('chapter_id', $firstChapter->id)->orderBy('id')->first();
        return redirect()->route('employee.view_page', ['itemId' => $firstItem->id]);
    }
}

public function viewPage($itemId)
{
    $item = Item::findOrFail($itemId);
    $module = $item->chapter->module;
    $chapters = Chapter::where('module_id', $module->id)->get();
    //$items = Item::whereIn('chapter_id', $chapters->pluck('id'))->get()->groupBy('chapter_id');
    $items = Item::whereIn('chapter_id', $chapters->pluck('id'))->orderBy('order')->get()->groupBy('chapter_id');

    return view('employee.view-page', compact('module', 'chapters', 'items', 'item'));
}

protected function ensureAllItemProgressRecordsExist($userId, $companyId, $moduleId)
{
    $chapters = Chapter::where('module_id', $moduleId)->orderBy('id')->get();
    foreach ($chapters as $chapter) {
        $items = Item::where('chapter_id', $chapter->id)->orderBy('order')->get();

        foreach ($items as $item) {
            $itemProgress = ItemProgress::where('UserID', $userId)
                                        ->where('CompanyID', $companyId)
                                        ->where('ModuleID', $moduleId)
                                        ->where('ItemID', $item->id)
                                        ->first();

            if (!$itemProgress) {
                // $maxOrder = ItemProgress::where('UserID', $userId)
                //                         ->where('CompanyID', $companyId)
                //                         ->where('ModuleID', $moduleId)
                //                         ->max('order');

                // $order = $maxOrder ? $maxOrder + 1 : 1;

                ItemProgress::create([
                    'UserID' => $userId,
                    'CompanyID' => $companyId,
                    'ModuleID' => $moduleId,
                    'ItemID' => $item->id,
                    'IsCompleted' => null,
                    'order' => $item->order,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
}
    

?>
