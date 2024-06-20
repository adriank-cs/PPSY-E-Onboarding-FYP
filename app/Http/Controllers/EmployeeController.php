<?php 

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Quiz;
use App\Models\QuizQuestion;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;


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


public function myModules()
{
    $user = auth()->user();
    $companyId = $user->companyUser->CompanyID;

    // Fetch all assigned modules for the user
    $assignedModules = AssignedModule::where('UserID', $user->id)
        ->with('module') // Eager load the module relationship
        ->get();

    $inProgressModules = [];
    $completedModules = [];
    $overdueModules = [];

    $currentDate = date('Y-m-d');

    foreach ($assignedModules as $assignedModule) {
        $module = $assignedModule->module;
        $totalChapters = $module->chapters()->count();
        $completedChapters = ChapterProgress::where('UserID', $user->id)
            ->where('CompanyID', $companyId)
            ->where('ModuleID', $module->id)
            ->where('IsCompleted', 1)
            ->count();

        $completionPercentage = ($totalChapters > 0) ? ($completedChapters / $totalChapters) * 100 : 0;
        $module->completion_percentage = round($completionPercentage);
        $module->progress = $completionPercentage;

        if ($completionPercentage == 100) {
            $completedModules[] = $module;
        } elseif ($completionPercentage < 100 && $assignedModule->due_date >= $currentDate) {
            $inProgressModules[] = $module;
        } elseif ($completionPercentage < 100 && $assignedModule->due_date < $currentDate) {
            $overdueModules[] = $module;
        }
    }

    return view('employee.my-modules', compact('inProgressModules', 'completedModules', 'overdueModules'));
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

    // public function viewPage($itemId)
    // {
    //     $item = Item::findOrFail($itemId);
    //     $module = $item->chapter->module;
    //     $chapters = Chapter::where('module_id', $module->id)->get();
    //     //$items = Item::whereIn('chapter_id', $chapters->pluck('id'))->get()->groupBy('chapter_id');
    //     $items = Item::whereIn('chapter_id', $chapters->pluck('id'))->orderBy('order')->get()->groupBy('chapter_id');
    //     $pdfAttachments = json_decode($item->pdf_attachments, true);

    //     return view('employee.view-page', compact('module', 'chapters', 'items', 'item','pdfAttachments'));
    // }

    public function viewPage($itemId){

        $item = Item::findOrFail($itemId);
        $module = $item->chapter->module;
        $chapters = Chapter::where('module_id', $module->id)->get();
        //$items = Item::whereIn('chapter_id', $chapters->pluck('id'))->get()->groupBy('chapter_id');
        $items = Item::whereIn('chapter_id', $chapters->pluck('id'))->orderBy('order')->get()->groupBy('chapter_id');
        $pdfAttachments = json_decode($item->pdf_attachments, true);

         // Check if the item has an associated quiz
        $quiz = Quiz::where('item_id', $itemId)->first();
        $quizQuestions = null;
        if ($quiz) {
            $quizQuestions = QuizQuestion::where('quiz_id', $quiz->id)->get();
        }

        return view('employee.view-page', compact('module', 'chapters', 'items', 'item','pdfAttachments', 'quiz', 'quizQuestions'));

    }

    protected function ensureAllItemProgressRecordsExist($userId, $companyId, $moduleId)
    {
        $chapters = Chapter::where('module_id', $moduleId)->orderBy('id')->get();
        foreach ($chapters as $chapter) {

            $chapterProgress = ChapterProgress::where('UserID', $userId)
                                           ->where('CompanyID', $companyId)
                                           ->where('ModuleID', $moduleId)
                                           ->where('ChapterID', $chapter->id)
                                           ->first();

        if (!$chapterProgress) {
            ChapterProgress::create([
                'UserID' => $userId,
                'CompanyID' => $companyId,
                'ModuleID' => $moduleId,
                'ChapterID' => $chapter->id,
                'IsCompleted' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

            $items = Item::where('chapter_id', $chapter->id)->orderBy('order')->get();

            foreach ($items as $item) {
                $itemProgress = ItemProgress::where('UserID', $userId)
                                            ->where('CompanyID', $companyId)
                                            ->where('ModuleID', $moduleId)
                                            ->where('ItemID', $item->id)
                                            ->first();

                if ($itemProgress) {
                    // Update the order if it has changed
                    if ($itemProgress->order != $item->order) {
                        $itemProgress->update(['order' => $item->order]);
                    }
                } else {
                    // Create new item progress record
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

public function submitQuiz(Request $request, $quizId)
{
    $quiz = Quiz::find($quizId);
    $questions = $quiz->questions;

    $score = 0;
    $feedback = [];

    foreach ($questions as $question) {
        $correctAnswers = json_decode($question->correct_answers, true);
        $userAnswer = $request->input('answers.' . $question->id);
        $isCorrect = false;

        if ($question->type == 'multiple_choice' || $question->type == 'checkbox') {
            if ($userAnswer == $correctAnswers || (is_array($userAnswer) && !array_diff($userAnswer, $correctAnswers) && !array_diff($correctAnswers, $userAnswer))) {
                $isCorrect = true;
                $score++;
            }
        } elseif ($question->type == 'short_answer') {
            $pattern = '/\b' . preg_quote($correctAnswers, '/') . '\b/i';
            if (preg_match($pattern, $userAnswer)) {
                $score++;
                $isCorrect = true;
            }
        }

        $feedback[] = [
            'questionId' => $question->id,
            'isCorrect' => $isCorrect
        ];
    }

    $passed = $score >= $quiz->passing_score;

    if ($passed) {
        $request->merge(['itemId' => $quiz->item_id]);
        $markCompletedResponse = $this->markCompleted($request, $quiz->item_id);

        // Log quiz completion activity
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'quiz' => $quizId,
                'score' => $score,
                'passed' => $passed
            ])
            ->event('Quiz Completion')
            ->log('Quiz completed by user ' . auth()->user()->name);

        if ($markCompletedResponse instanceof \Illuminate\Http\JsonResponse) {
            $markCompletedData = $markCompletedResponse->getData(true);
            $markCompletedData['feedback'] = $feedback;
            $markCompletedData['passed'] = $passed;
            return response()->json($markCompletedData);
        } else {
            return $markCompletedResponse->with(['feedback' => $feedback, 'passed' => $passed]);
        }
    } else {
        return response()->json([
            'feedback' => $feedback,
            'passed' => $passed
        ]);
    }
}

    public function markCompleted(Request $request, $itemId)
{
    $user = auth()->user();
    $companyId = $user->companyUser->CompanyID;

    // Update the current item's progress to completed
    $itemProgress = ItemProgress::where('UserID', $user->id)
                                ->where('ItemID', $itemId)
                                ->firstOrFail();
    $itemProgress->IsCompleted = 1;
    $itemProgress->save();

    // Get the module ID of the current item
    $item = Item::findOrFail($itemId);
    $moduleId = $item->chapter->module->id;
    $chapterId = $item->chapter_id;

    // Get the next incomplete item in the same chapter
    $nextItemProgress = ItemProgress::where('UserID', $user->id)
                                    ->where('CompanyID', $companyId)
                                    ->where('ModuleID', $moduleId)
                                    ->whereHas('item', function ($query) use ($chapterId) {
                                        $query->where('chapter_id', $chapterId);
                                    })
                                    ->whereNull('IsCompleted')
                                    ->orderBy('order')
                                    ->first();

    if (!$nextItemProgress) {
        // If no more items in the current chapter, mark the chapter as completed
        $chapterProgress = ChapterProgress::where('UserID', $user->id)
                                          ->where('CompanyID', $companyId)
                                          ->where('ModuleID', $moduleId)
                                          ->where('ChapterID', $chapterId)
                                          ->first();

        if ($chapterProgress) {
            $chapterProgress->IsCompleted = 1;
            $chapterProgress->save();

            // Log chapter completion activity
            activity()
                ->causedBy($user)
                ->withProperties([
                    'chapter' => $chapterId,
                    'module' => $moduleId
                ])
                ->event('Chapter Completion')
                ->log('Chapter completed by user ' . $user->name);
        }

        // Move to the next chapter if available
        $nextChapter = Chapter::where('module_id', $moduleId)
                              ->where('id', '>', $chapterId)
                              ->orderBy('id')
                              ->first();

        if ($nextChapter) {
            $nextItemProgress = ItemProgress::where('UserID', $user->id)
                                            ->where('CompanyID', $companyId)
                                            ->where('ModuleID', $moduleId)
                                            ->whereHas('item', function ($query) use ($nextChapter) {
                                                $query->where('chapter_id', $nextChapter->id);
                                            })
                                            ->whereNull('IsCompleted')
                                            ->orderBy('order')
                                            ->first();
        }else {
            // If no more chapters, check if all chapters are completed to log module completion
            $allChaptersCompleted = ChapterProgress::where('UserID', $user->id)
                                                   ->where('CompanyID', $companyId)
                                                   ->where('ModuleID', $moduleId)
                                                   ->whereNull('IsCompleted')
                                                   ->count() == 0;

            if ($allChaptersCompleted) {
                // Log module completion activity
                activity()
                    ->causedBy($user)
                    ->withProperties([
                        'module' => $moduleId
                    ])
                    ->event('Module Completion')
                    ->log('Module completed by user ' . $user->name);
            }
        }
    }

    if ($nextItemProgress) {
        $redirectUrl = route('employee.view_page', ['itemId' => $nextItemProgress->ItemID]);
    } else {
        // No more items, redirect to a completion page or somewhere appropriate
        $redirectUrl = route('employee.module_complete', ['moduleId' => $moduleId]);
    }

    // Return JSON response
    return response()->json(['redirect' => $redirectUrl]);
}

    public function moduleComplete($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        return view('employee.completion-module', compact('module'));
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

        return view('employee.find-colleagues', compact('adminUsers', 'nonAdminUsers'));
    }

    public function colleagueDetails($id)
    {
        $colleague = User::with('profile')->findOrFail($id);
        return view('employee.colleague-details', compact('colleague'));
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

        return view('employee.leaderboard', compact('users', 'rank'));
    }


}
    

?>
