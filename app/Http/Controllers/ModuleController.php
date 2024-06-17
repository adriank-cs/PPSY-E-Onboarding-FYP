<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Quiz;
use App\Models\QuizQuestion;
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
        ]);

        $module = Module::find($id);
        $moduleCurrentImage = $module->image_path;

        $module->update([
            'title' => $request->title,
            'image_path' => $moduleCurrentImage,
        ]);

        if ($request->input('croppedImage')) {

            $croppedImage = $request->input('croppedImage');
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
            $fileName = time() . '.jpg';
            $path = 'modules/' . $fileName;
            Storage::disk('public')->put($path, $imageData);
            $module->update(['image_path' => $path]);

        }

        

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

        //get module
        $module = Module::find($moduleId);

        // Fetch modules belonging to the company ID of the currently logged-in admin
        $chapters = Chapter::where('module_id', $moduleId)->get();

        // Pass the profiles to the view
        return view('admin.manage-chapters', ['chapters' => $chapters, 'moduleId' => $moduleId, 'module' => $module]);

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
        return view('admin.manage-pages', ['pages' => $pages, 'chapterId' => $chapterId, 'moduleId' => $moduleId, 'chapter' => $chapter]);

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


    public function add_quizPost(Request $request, $chapterId)
{
    // Validate the form data
    $request->validate([
        'title' => 'required|string|max:255',
        'passing_score' => 'required|integer|min:0',
        'questions' => 'required|array',
        'questions.*' => 'required|string',
        'question_types' => 'required|array',
        'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
        'answers' => 'array',
        'answers.*' => 'array',
        'correct_answers' => 'required|array',
        'correct_answers.*' => 'required',
    ]);

    // Fetch the latest order for the given chapter
    $latestOrder = Item::where('chapter_id', $chapterId)->max('order');
    $newOrder = $latestOrder ? $latestOrder + 1 : 1;

    // Create and save the item
    $item = Item::create([
        'title' => $request->title,
        'chapter_id' => $chapterId,
        'description' => $request->description,
        'content' => $request->content,
        'pdf_attachments' => null, // Assuming quizzes don't have PDF attachments
        'order' => $newOrder,
    ]);

    // Create the quiz and associate it with the item
    $quiz = Quiz::create([
        'title' => $request->title,
        'item_id' => $item->id,
        'passing_score' => $request->passing_score,
    ]);

    // Save quiz questions
    foreach ($request->questions as $index => $question) {
        $answers = $request->question_types[$index] === 'short_answer' ? [] : $request->answers[$index];
        $correctAnswers = $request->question_types[$index] === 'short_answer' ? $request->correct_answers[$index][0] : $request->correct_answers[$index];

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => $question,
            'type' => $request->question_types[$index],
            'answer_options' => json_encode($answers),
            'correct_answers' => json_encode($correctAnswers),
        ]);
    }

    // Redirect back to the configure module page
    return redirect()->route('admin.manage_page', ['id' => $chapterId])
                    ->with('success', 'Quiz added successfully!');
}

// public function updateQuiz(Request $request, $id)
// {
//     $quiz = Quiz::find($id);

//     $request->validate([
//         'title' => 'required|string|max:255',
//         'passing_score' => 'required|integer|min:0',
//         'questions' => 'required|array',
//         'questions.*' => 'required|string',
//         'question_types' => 'required|array',
//         'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
//         'answers' => 'array',
//         'answers.*' => 'array',
//         'correct_answers' => 'required|array',
//     ]);

//     $quiz->title = $request->title;
//     $quiz->passing_score = $request->passing_score;
//     $quiz->save();

//     QuizQuestion::where('quiz_id', $quiz->id)->delete();

//     foreach ($request->questions as $index => $question) {
//         $questionData = [
//             'quiz_id' => $quiz->id,
//             'question' => $question,
//             'type' => $request->question_types[$index],
//             'answer_options' => json_encode($request->answers[$index]),
//         ];

//         if ($request->question_types[$index] == 'multiple_choice') {
//             $questionData['correct_answers'] = json_encode($request->correct_answers[$index]);
//         } elseif ($request->question_types[$index] == 'checkbox') {
//             $questionData['correct_answers'] = json_encode($request->correct_answers[$index]);
//         } else {
//             $questionData['correct_answers'] = json_encode($request->correct_answers[$index][0]);
//         }

//         QuizQuestion::create($questionData);
//     }

//     return redirect()->route('admin.edit_page', ['id' => $quiz->item_id])->with('success', 'Quiz updated successfully');
// }

public function updateQuiz(Request $request, $id)
{
    $quiz = Quiz::find($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'passing_score' => 'required|integer|min:0',
        'questions' => 'required|array',
        'questions.*' => 'required|string',
        'question_types' => 'required|array',
        'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
        'answers' => 'array',
        'answers.*' => 'array',
        'correct_answers' => 'required|array',
    ]);

    $quiz->title = $request->title;
    $quiz->passing_score = $request->passing_score;
    $quiz->save();

    // Fetch existing questions to update or delete
    $existingQuestions = QuizQuestion::where('quiz_id', $quiz->id)->get();
    $existingQuestionIds = $existingQuestions->pluck('id')->toArray();

    foreach ($request->questions as $index => $question) {
        $questionData = [
            'quiz_id' => $quiz->id,
            'question' => $question,
            'type' => $request->question_types[$index],
            'answer_options' => json_encode($request->answers[$index] ?? []), // Use an empty array if answers are not set
        ];

        if ($request->question_types[$index] == 'multiple_choice') {
            $questionData['correct_answers'] = json_encode($request->correct_answers[$index] ?? []);
        } elseif ($request->question_types[$index] == 'checkbox') {
            $questionData['correct_answers'] = json_encode($request->correct_answers[$index] ?? []);
        } else {
            $questionData['correct_answers'] = json_encode($request->correct_answers[$index][0] ?? '');
        }

        // Update or create the question
        if (isset($existingQuestionIds[$index])) {
            QuizQuestion::where('id', $existingQuestionIds[$index])->update($questionData);
        } else {
            QuizQuestion::create($questionData);
        }
    }

    // Delete any remaining old questions that were not updated
    $updatedQuestionIds = array_slice($existingQuestionIds, 0, count($request->questions));
    QuizQuestion::where('quiz_id', $quiz->id)->whereNotIn('id', $updatedQuestionIds)->delete();

    return redirect()->route('admin.edit_page', ['id' => $quiz->item_id])->with('success', 'Quiz updated successfully');
}



    // public function editPage($id)
    // {
    //     $page = Item::find($id);
    //     $pdfAttachments = json_decode($page->pdf_attachments, true) ?? [];
    //     return view('admin.edit-page', compact('page', 'pdfAttachments'));
    // }

    public function editPage($id)
{
    $page = Item::find($id);

    // Check if the item is a quiz
    $quiz = Quiz::where('item_id', $id)->first();

    if ($quiz) {
        // Fetch the quiz questions
        $quizQuestions = QuizQuestion::where('quiz_id', $quiz->id)->get();
        return view('admin.edit-quiz', compact('quiz', 'quizQuestions'));
    } else {
        $pdfAttachments = json_decode($page->pdf_attachments, true) ?? [];
        return view('admin.edit-page', compact('page', 'pdfAttachments'));
    }
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

         // Check if the item has an associated quiz
        $quiz = Quiz::where('item_id', $id)->first();
        $quizQuestions = null;
        if ($quiz) {
            $quizQuestions = QuizQuestion::where('quiz_id', $quiz->id)->get();
        }

        return view('admin.view-page', compact('viewpage', 'chapter', 'module', 'chapters', 'pages', 'pdfAttachments', 'quiz', 'quizQuestions'));

    }

    public function assignModule($id){
        $moduleId = $id;

        $adminUser = auth()->user();

        $companyId = $adminUser->companyUser->CompanyID;

        $users = User::join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->leftJoin('assigned_module', function($join) use ($moduleId) {
            $join->on('users.id', '=', 'assigned_module.UserID')
                 ->where('assigned_module.ModuleID', '=', $moduleId);
        })
        ->where('companyusers.CompanyID', '=', $companyId)
        ->whereNull('assigned_module.ModuleID') // Exclude users already assigned to this module
        ->where('companyusers.isAdmin', '=', 0) // Exclude admins
        ->get(['users.*']);

        return view('admin.assign-module', compact('users', 'moduleId'));
    }
    public function unassignModule($id){
        $moduleId = $id;

        $adminUser = auth()->user();

        $companyId = $adminUser->companyUser->CompanyID;

        $users = User::join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->join('assigned_module', function($join) use ($moduleId) {
            $join->on('users.id', '=', 'assigned_module.UserID')
                 ->where('assigned_module.ModuleID', '=', $moduleId);
        })
        ->where('companyusers.CompanyID', '=', $companyId)
        ->where('companyusers.isAdmin', '=', 0) // Exclude admins
        ->get(['users.*']);

        return view('admin.unassign-module', compact('users', 'moduleId'));
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

    public function unassignModulePost(Request $request){
        $request->validate([
            'user' => 'required|exists:users,id',
        ]);


        $adminUser = auth()->user();
        $userId = $request->input('user');
        $moduleId = $request->input('module_id');

        $assignedModule = AssignedModule::where('UserID', $userId)
                                    ->where('ModuleID', $moduleId);

        if ($assignedModule) {
            $assignedModule->delete();
            return redirect()->back()->with('success', 'Module unassigned successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to unassign module.');
        }
    }

    public function getDueDate($moduleId, $userId)
{
    $assignedModule = AssignedModule::where('ModuleID', $moduleId)
                                    ->where('UserID', $userId)
                                    ->first();

    return response()->json(['due_date' => $assignedModule ? $assignedModule->due_date : null]);
}

public function configureDueDate($id){
    $moduleId = $id;

    $adminUser = auth()->user();

    $companyId = $adminUser->companyUser->CompanyID;

    $users = User::join('companyusers', 'users.id', '=', 'companyusers.UserID')
    ->join('assigned_module', function($join) use ($moduleId) {
        $join->on('users.id', '=', 'assigned_module.UserID')
             ->where('assigned_module.ModuleID', '=', $moduleId);
    })
    ->where('companyusers.CompanyID', '=', $companyId)
    ->where('companyusers.isAdmin', '=', 0) // Exclude admins
    ->get(['users.*']);

    return view('admin.configure-duedate', compact('users', 'moduleId'));
}

public function configureDueDatePost(Request $request){
    $request->validate([
        'user' => 'required|exists:users,id',
        'due_date' => 'required|date',
    ]);

    $userId = $request->input('user');
    $adminUser = auth()->user();

    $companyId = $adminUser->companyUser->CompanyID;
    $moduleId = $request->input('module_id');

    // Fetch the existing record
    $assignedModule = AssignedModule::where('UserID', $userId)
                                    ->where('CompanyID', $companyId)
                                    ->where('ModuleID', $moduleId)
                                    ->first();

    if ($assignedModule) {
        // Update the existing record
        $assignedModule->due_date = $request->input('due_date');
        $assignedModule->updated_at = now();
        $assignedModule->save();

        return redirect()->back()->with('success', 'Due date changed successfully.');
    } else {
        // Handle the case where the record does not exist
        return redirect()->back()->with('error', 'Assigned module not found.');
    }
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

    public function nextPage($itemId)
{
    // Get the current item
    $currentItem = Item::findOrFail($itemId);
    $chapterId = $currentItem->chapter_id;
    $moduleId = $currentItem->chapter->module_id;

    // Find the next item within the same chapter based on the order
    $nextItem = Item::where('chapter_id', $chapterId)
                    ->where('order', '>', $currentItem->order)
                    ->orderBy('order')
                    ->first();

    if ($nextItem) {
        // Redirect to the next item
        return response()->json(['redirect' => route('admin.view_page', ['id' => $nextItem->id])]);
    } else {
        // No next item in the current chapter, find the next chapter
        $nextChapter = Chapter::where('module_id', $moduleId)
                              ->where('id', '>', $chapterId)
                              ->orderBy('id')
                              ->first();

        if ($nextChapter) {
            // Get the first item of the next chapter
            $firstItemOfNextChapter = Item::where('chapter_id', $nextChapter->id)
                                          ->orderBy('order')
                                          ->first();

            if ($firstItemOfNextChapter) {
                // Redirect to the first item of the next chapter
                return response()->json(['redirect' => route('admin.view_page', ['id' => $firstItemOfNextChapter->id])]);
            } else {
                // Handle case where the next chapter has no items
                return response()->json(['message' => 'Next chapter has no items']);
            }
        } else {
            // No next chapter, handle accordingly (e.g., stay on the current page or show a message)
            return response()->json(['redirect' => route('admin.module_complete', ['moduleId' => $moduleId])]);
        }
    }
}

public function moduleComplete($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        return view('admin.completion-module', compact('module'));
    }


}
