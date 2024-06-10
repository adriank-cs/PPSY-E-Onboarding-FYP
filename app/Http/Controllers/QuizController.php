<?php
/*  QuizController.php */

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\UserResponse;
use App\Models\QuizQuestion;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // Employee view quiz
    public function index()
    {
        $user = Auth::user();
        $quizzes = Quiz::where('company_id', $user->companyUser->CompanyID)->get(); 
        return view('employee.onboarding-quiz', compact('quizzes'));
    }

    // Admin view quiz
    public function adminViewQuiz()
    {
        $user = Auth::user();
        $quizzes = Quiz::where('company_id', $user->companyUser->CompanyID)->get();;
        return view('admin.onboarding-quiz', compact('quizzes'));
    }

    // Admin create quiz
    public function create()
    {
        return view('admin.create-quiz');
    }

    // Store a newly created quiz
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'attempt_limit' => 'required|integer|min:1', // Validate attempt limit
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|min:3',
            'question_types' => 'required|array|min:1',
            'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
            // 'attempt_limit' => 'required|integer|min:1', // Validate attempt limit
        ], [
            'questions.*.required' => 'The question field is required.',
            'questions.*.min' => 'The question field must be at least 3 characters.',
            'question_types.*.required' => 'The question type field is required.',
            'question_types.*.in' => 'The question type field must be one of: multiple_choice, short_answer, checkbox',
        ]);

        $user = auth()->user();;
        
        $quiz = Quiz::create([
            'title' => $request->input('title'),
            'attempt_limit' => $request->input('attempt_limit'), // Save attempt limit
            'company_id' => $user->companyUser->CompanyID,
        ]);

        foreach ($request->questions as $key => $question) {
            $quizQuestion = new QuizQuestion;
            $quizQuestion->quiz_id = $quiz->id;
            $quizQuestion->question = $question;
            $quizQuestion->type = $request->input('question_types')[$key];

            if (in_array($quizQuestion->type, ['multiple_choice', 'checkbox'])) {
                $answerOptions = $request->input('answers')[$key] ?? [];
                if (!empty($answerOptions)) {
                    $quizQuestion->answer_options = json_encode($answerOptions);
                }
            }

            $quizQuestion->save();
        }

        return redirect()->route('admin.onboarding-quiz')->with('success', 'Quiz created successfully!');
    }

    // Admin edit quiz
    public function editQuiz(Quiz $quiz)
    {
        $user = Auth::user();

        // if ($quiz->company_id !== $user->company_id) {
        //     return redirect()->route('admin.onboarding-quiz')->with('error', 'You do not have permission to edit this quiz.');
        // }

        $questions = $quiz->questions;

        foreach ($questions as $question) {
            if (($question->type === 'multiple_choice' || $question->type === 'checkbox') && is_null($question->answer_options)) {
                $question->answer_options = json_encode([]);
            }
        }

        return view('admin.edit-quiz', compact('quiz', 'questions'));
    }

    // Admin update quiz
    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'attempt_limit' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.type' => 'required|in:multiple_choice,short_answer,checkbox',
            'questions.*.answer_options' => 'required_if:questions.*.type,multiple_choice|array|min:1',
            'questions.*.answer_options' => 'required_if:questions.*.type,checkbox|array|min:1',
            'questions.*.answer_options.*' => 'nullable|string|max:255',
        ]);

        $quiz->update([
            'title' => $request->title,
            'attempt_limit' => $request->attempt_limit,
        ]);

        $existingQuestionIds = $quiz->questions->pluck('id')->toArray();

        foreach ($request->questions as $questionData) {
            $answerOptions = ($questionData['type'] === 'multiple_choice' || $questionData['type'] === 'checkbox') ? array_filter($questionData['answer_options']) : null;

            $question = QuizQuestion::find($questionData['id'] ?? 0);

            if ($question) {
                $question->update([
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'answer_options' => $answerOptions ? json_encode($answerOptions) : null,
                ]);

                $existingQuestionIds = array_diff($existingQuestionIds, [$question->id]);
            } else {
                $newQuestion = QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'answer_options' => $answerOptions ? json_encode($answerOptions) : null,
                ]);
            }
        }

        QuizQuestion::destroy($existingQuestionIds);

        return redirect()->route('quizzes.edit', $quiz->id)->with('success', 'Quiz updated successfully.');
    }

    // Admin delete quiz
    // public function delete(Quiz $quiz)
    // {
    //     // Delete the quiz
    //     $quiz->delete();

    //     return redirect()->route('admin.onboarding-quiz')->with('success', 'Quiz deleted successfully.');
    // }

    public function delete(Quiz $quiz)
{
    // Check if the quiz belongs to the user's company
    $user = Auth::user();
    if ($quiz->company_id !== $user->companyUser->CompanyID) {
        return redirect()->route('admin.onboarding-quiz')->with('error', 'You do not have permission to delete this quiz.');
    }

    // Delete user responses related to the quiz
    UserResponse::whereIn('quiz_question_id', $quiz->questions->pluck('id'))->delete();

    // Delete user quiz attempts related to the quiz
    UserQuizAttempt::where('quiz_id', $quiz->id)->delete();

    // Delete the quiz questions
    QuizQuestion::where('quiz_id', $quiz->id)->delete();

    // Delete the quiz
    $quiz->delete();

    return redirect()->route('admin.onboarding-quiz')->with('success', 'Quiz deleted successfully.');
}


    // Show quiz details
    public function show(Quiz $quiz)
    {
        $user = Auth::user();

        // if ($quiz->company_id !== $user->company_id) {
        //     return redirect()->route('employee.onboarding-quiz')->with('error', 'You do not have permission to view this quiz.');
        // }

        $attempts = $quiz->attempts()->where('user_id', $user->id)->count();

        if ($attempts >= $quiz->attempt_limit) {
            $userResponses = $user->responses()
                ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
                ->get()
                ->keyBy('quiz_question_id');

            $answeredQuestions = $userResponses->count();
            $completionPercentage = $quiz->questions->count() > 0 ? round(($answeredQuestions / $quiz->questions->count()) * 100) : 0;

            return view('employee.quiz-details', compact('quiz', 'completionPercentage', 'userResponses', 'attempts'));
        }
        else {
            $userResponses = $user->responses()
                ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
                ->get()
                ->keyBy('quiz_question_id');

            $answeredQuestions = $userResponses->count();
            $completionPercentage = $quiz->questions->count() > 0 ? round(($answeredQuestions / $quiz->questions->count()) * 100) : 0;

            return view('employee.quiz-details', compact('quiz', 'completionPercentage', 'userResponses', 'attempts'));
        }
    }

   // Submit quiz answers
   public function submitAnswers(Quiz $quiz, Request $request)
   {
       $user = Auth::user();

       if ($quiz->company_id !== $user->company_id) {
           return redirect()->route('quizzes.show', $quiz->id)->with('error', 'You do not have permission to submit answers for this quiz.');
       }

       $attempts = $quiz->attempts()->where('user_id', $user->id)->count();

       if ($attempts >= $quiz->attempt_limit) {
           return redirect()->route('quizzes.show', $quiz->id)->with('error', 'You have reached the maximum number of attempts for this quiz.');
       }

       try {
           $answers = $request->input('answers');

           foreach ($answers as $questionId => $answer) {
               UserResponse::updateOrCreate([
                   'user_id' => $user->id,
                   'quiz_question_id' => $questionId,
               ], [
                   'answer' => $answer,
               ]);
           }

           UserQuizAttempt::create([
               'user_id' => $user->id,
               'quiz_id' => $quiz->id,
           ]);

           session()->put('quiz_completed_' . $quiz->id, true);
           $request->session()->flash('success', 'Answers submitted successfully!');
           return redirect()->route('quizzes.show', $quiz->id);
       } catch (\Exception $e) {
           report($e);
           return abort(500, 'Error submitting answers.');
       }
   }

   // View quiz details and user responses
   public function getDetails(Quiz $quiz)
   {
       $user = Auth::user();

       if ($quiz->company_id !== $user->company_id) {
           return redirect()->route('employee.onboarding-quiz')->with('error', 'You do not have permission to view details for this quiz.');
       }

       $userResponses = $user->responses()
           ->with('question.quiz')
           ->where('user_id', $user->id)
           ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
           ->get();

       $formattedAnswers = [];
       foreach ($userResponses as $response) {
           if ($response->question) {
               $question = $response->question;
               $answerArray = unserialize($response->answer);

               $formattedAnswers[$question->id] = $answerArray;
           } else {
               // Handle missing question relationship
           }
       }

       return view('employee.view-responses', compact('quiz', 'formattedAnswers'));
   }
}     

