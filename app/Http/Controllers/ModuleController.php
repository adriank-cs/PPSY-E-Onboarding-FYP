<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemProgress;
use App\Models\Module;
use App\Models\Chapter;
use App\Models\User;
use App\Models\AssignedModule;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage for image handling
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser;



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
            'croppedImage' => 'required|string',
        ]);
    

         // Handle the cropped image (Base64 encoded)
        $croppedImage = $request->input('croppedImage');
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
        $fileName = time() . '.jpg';
        $path = 'modules/' . $fileName;
        Storage::disk('public')->put($path, $imageData);
    
        
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
            'due_date' => 'required|date',
        ]);

        $module = Module::find($id);

        if ($request->hasFile('croppedImage')) {
            // $image = $request->file('image');
            // $imagePath = $image->storeAs('modules', $image->getClientOriginalName(), 'public');

            // $module->update(['image_path' => $imagePath]);

            $croppedImage = $request->input('croppedImage');
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
            $fileName = time() . '.jpg';
            $path = 'modules/' . $fileName;
            Storage::disk('public')->put($path, $imageData);

            $module->update(['image_path' => $path]);

        }

        $module->update([
            'title' => $request->title,
            'due_date' => $request->due_date,
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

        $chapter = Chapter::find($id);
        $moduleId = $chapter->module_id;
        $chapterId = $id;

        // Fetch modules belonging to the company ID of the currently logged-in admin
        $pages = Item::where('chapter_id', $chapterId)->orderBy('order')->get();

        // Pass the profiles to the view
        return view('admin.manage-pages', ['pages' => $pages, 'chapterId' => $chapterId, 'moduleId' => $moduleId]);

    }

    public function updatePageOrder(Request $request)
{
    $order = $request->input('order');

    foreach ($order as $index => $id) {
        $page = Item::find($id);
        $page->order = $index + 1;
        $page->save();
    }

    return response()->json(['status' => 'success']);
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
            'pdfAttachments.*' => 'mimes:pdf|max:20480',
        ]);

        $pdfAttachmentPaths = [];

        if ($request->hasFile('pdfAttachments')) {
            foreach ($request->file('pdfAttachments') as $pdfFile) {
                $path = $pdfFile->store('pdf_attachments', 'public');
                $pdfAttachmentPaths[] = [
                    'url' => Storage::url($path),
                    'name' => $pdfFile->getClientOriginalName(),
                ];
            }
        }

        // Fetch the latest order for the given chapter
    $latestOrder = Item::where('chapter_id', $chapterId)->max('order');
    $newOrder = $latestOrder ? $latestOrder + 1 : 1;
    
        // Create and save the chapter
        Item::create([
            'title' => $request->title,
            'chapter_id' => $chapterId,
            'description' => $request->description,
            'content' => $request->content,
            'pdf_attachments' => json_encode($pdfAttachmentPaths),
            'order' => $newOrder,
        ]);
    
        // Redirect back to the configure module page
        return redirect()->route('admin.manage_page', ['id' => $chapterId])
                         ->with('success', 'Page added successfully!');
    }

    public function editPage($id)
    {
        $page = Item::find($id);
        $pdfAttachments = json_decode($page->pdf_attachments, true) ?? [];
        return view('admin.edit-page', compact('page', 'pdfAttachments'));
    }

    public function editPagePost(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'pdfAttachments.*' => 'mimes:pdf|max:20480',
        ]);


        $page = Item::find($id);

        $pdfAttachmentPaths = json_decode($page->pdf_attachments, true) ?? [];

        // Remove selected existing files
    if ($request->has('removeExistingFiles')) {
        foreach ($request->input('removeExistingFiles') as $fileUrl) {
            $key = array_search($fileUrl, array_column($pdfAttachmentPaths, 'url'));
            if ($key !== false) {
                // Delete the file from storage
                Storage::disk('public')->delete(str_replace('/storage/', '', $fileUrl));
                // Remove from the array
                unset($pdfAttachmentPaths[$key]);
            }
        }
    }

    // Add new files
    if ($request->hasFile('pdfAttachments')) {
        foreach ($request->file('pdfAttachments') as $pdfFile) {
            $path = $pdfFile->store('pdf_attachments', 'public');
            $pdfAttachmentPaths[] = [
                'url' => Storage::url($path),
                'name' => $pdfFile->getClientOriginalName(),
            ];
        }
    }

        $page->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'pdf_attachments' => json_encode(array_values($pdfAttachmentPaths)), // Re-index array
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

    public function viewPage($id){

        $viewpage = Item::find($id);
        $chapterId = $viewpage->chapter_id;
        $chapter = Chapter::find($chapterId);
        $moduleId = Chapter::find($chapterId)->module_id;
        $module = Module::find($moduleId);
        $chapters = Chapter::where('module_id', $moduleId)->get();
        //$pages = Item::whereIn('chapter_id', $chapters->pluck('id'))->get()->groupBy('chapter_id');
        $pdfAttachments = json_decode($viewpage->pdf_attachments, true);
        $pages = Item::whereIn('chapter_id', $chapters->pluck('id'))->orderBy('order')->get()->groupBy('chapter_id');

        return view('admin.view-page', compact('viewpage', 'chapter', 'module', 'chapters', 'pages','pdfAttachments'));

    }

    public function assignModule($id){
        $moduleId = $id;

        $adminUser = auth()->user();

        $companyId = $adminUser->companyUser->CompanyID;

        //$users = CompanyUser::where('CompanyID', $companyId)->get();

        $users = User::join('companyusers', 'users.id', '=', 'companyusers.UserID')
            ->where('companyusers.CompanyID', '=', $companyId)
            ->get();

        return view('admin.assign-module', compact('users', 'moduleId'));
    }

    public function assignModulePost(Request $request){
        $request->validate([
            'user' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        $userId = $request->input('user');
        $adminUser = auth()->user();

        $companyId = $adminUser->companyUser->CompanyID;
        $moduleId = $request->input('module_id');

        // AssignedModule::create([
        //     'UserID' => $userId,
        //     'CompanyID' => $companyId,
        //     'ModuleID' => $moduleId,
        //     'DateAssigned' => now(),
        //     'due_date' => $request->input('due_date'),
        // ]);

        DB::table('assigned_module')->insert([
            'UserID' => $userId,
            'CompanyID' => $companyId,
            'ModuleID' => $moduleId,
            'DateAssigned' => now(),
            'due_date' => $request->input('due_date'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Module assigned successfully.');
    }

    public function uploadPdf(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:20480', // Validate PDF file
        ]);

        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $path = $pdf->store('pdfs', 'public'); // Store the uploaded PDF

            // Use PDFParser to extract text
            $parser = new Parser();
            $pdf = $parser->parseFile(storage_path('app/public/' . $path));
            $text = $pdf->getText();
            $pdf_with_line_breaks = nl2br($text);

            Storage::disk('public')->delete($path); // Delete the uploaded PDF

            return response()->json(['success' => true, 'text' => $pdf_with_line_breaks]);
        }

        return response()->json(['success' => false]);
    }


}
