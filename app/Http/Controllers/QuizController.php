<?php
// QuizController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\UserResponse;
use App\Models\QuizQuestion;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $quizzes = Quiz::where('company_id', $user->companyUser->CompanyID)->get();
        return view('admin.onboarding-quiz', compact('quizzes'));
    }

    // Admin create quiz
    public function create()
    {
        return view('admin.create-quiz');
    }

    // Admin store quiz
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'attempt_limit' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|min:3',
            'question_types' => 'required|array|min:1',
            'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
            'answers' => 'required|array|min:1',
            'answers.*' => 'array|min:2', // Ensure at least two answer options for multiple choice and checkbox
        ], [
            'questions.*.required' => 'The question field is required.',
            'questions.*.min' => 'The question field must be at least 3 characters.',
            'question_types.*.required' => 'The question type field is required.',
            'question_types.*.in' => 'The question type field must be one of: multiple_choice, short_answer, checkbox',
        ]);

        $user = auth()->user();
        
        $quiz = Quiz::create([
            'title' => $request->input('title'),
            'attempt_limit' => $request->input('attempt_limit'),
            'company_id' => $user->companyUser->CompanyID,
        ]);

        foreach ($request->questions as $key => $question) {
            $quizQuestion = new QuizQuestion;
            $quizQuestion->quiz_id = $quiz->id;
            $quizQuestion->question = $question;
            $quizQuestion->type = $request->input('question_types')[$key];

            if (in_array($quizQuestion->type, ['multiple_choice', 'checkbox'])) {
                $answerOptions = $request->input('answers')[$key] ?? [];
                $answerOptionIds = range(1, count($answerOptions)); // Generate sequential numerical IDs
                $quizQuestion->answer_options = json_encode($answerOptions);
                $quizQuestion->answer_option_id = json_encode($answerOptionIds);
            }

            $quizQuestion->save();
        }

        return redirect()->route('admin.onboarding-quiz')->with('success', 'Quiz created successfully!');
    }

    // Admin edit quiz
    public function editQuiz(Quiz $quiz)
    {
        $user = Auth::user();
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
            $answerOptions = in_array($questionData['type'], ['multiple_choice', 'checkbox']) ? array_filter($questionData['answer_options']) : [];
            $question = QuizQuestion::find($questionData['id'] ?? 0);

            if ($question) {
                // Ensure existing options and IDs are arrays
                $existingOptions = json_decode($question->answer_options, true) ?? [];
                $existingOptionIds = json_decode($question->answer_option_id, true) ?? [];

                if (!empty($answerOptions) && ($question->type === 'multiple_choice' || $question->type === 'checkbox')) {
                    if ($question->type === 'multiple_choice' && $questionData['type'] === 'multiple_choice') {
                        // Handle multiple choice
                        $updatedOptions = $answerOptions;
                        $updatedOptionIds = range(1, count($updatedOptions));

                        $question->answer_options = json_encode(array_values($updatedOptions));
                        $question->answer_option_id = json_encode($updatedOptionIds);
                    }

                    if ($question->type === 'checkbox' && $questionData['type'] === 'checkbox') {
                        // Handle checkbox
                        $updatedOptions = $answerOptions;
                        $updatedOptionIds = range(1, count($updatedOptions));

                        $question->answer_options = json_encode(array_values($updatedOptions));
                        $question->answer_option_id = json_encode($updatedOptionIds);
                    }
                } else {
                    // Reset options and IDs if the type does not match
                    $question->answer_options = json_encode($answerOptions);
                    $question->answer_option_id = json_encode(range(1, count($answerOptions)));
                }

                $question->question = $questionData['question'];
                $question->type = $questionData['type'];
                $question->save();

                $existingQuestionIds = array_diff($existingQuestionIds, [$question->id]);
            } else {
                // New question
                $newOptionIds = range(1, count($answerOptions));

                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'answer_options' => $answerOptions ? json_encode($answerOptions) : null,
                    'answer_option_id' => $answerOptions ? json_encode($newOptionIds) : null,
                ]);
            }
        }

        QuizQuestion::destroy($existingQuestionIds);

        return redirect()->route('quizzes.edit', $quiz->id)->with('success', 'Quiz updated successfully.');
    }

    public function delete(Quiz $quiz)
    {
        // Check if the quiz belongs to the user's company
        $user = Auth::user();
        if ($quiz->company_id !== $user->companyUser->CompanyID) {
            return redirect()->route('admin.onboarding-quiz')->with('error', 'You do not have permission to delete this quiz.');
        }
    
        // The relationships and cascading deletes will handle deletion of related questions and responses
        $quiz->delete();
    
        return redirect()->route('admin.onboarding-quiz')->with('success', 'Quiz deleted successfully.');
    }

    public function show(Quiz $quiz)
    {
        $user = Auth::user();
        
        $attempts = $quiz->attempts()->where('user_id', $user->id)->count();
        
        $userResponses = $user->responses()
            ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
            ->get()
            ->keyBy('quiz_question_id');
        
        $correctAnswers = DB::table('employee_correct_answers')
            ->where('user_id', $user->id)
            ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
            ->get()
            ->pluck('correct_answer', 'quiz_question_id')
            ->map(function ($item) {
                return json_decode($item, true);
            })
            ->toArray();
        
        $answeredQuestions = $userResponses->count();
        $completionPercentage = $quiz->questions->count() > 0 ? round(($answeredQuestions / $quiz->questions->count()) * 100) : 0;
        
        return view('employee.quiz-details', compact('quiz', 'completionPercentage', 'userResponses', 'attempts', 'correctAnswers'));
    }

    public function submitAnswers(Quiz $quiz, Request $request)
    {
        $user = Auth::user();
    
        $attempts = $quiz->attempts()->where('user_id', $user->id)->count();
    
        if ($attempts >= $quiz->attempt_limit) {
            return redirect()->route('quizzes.show', $quiz->id)->with('error', 'You have reached the maximum number of attempts for this quiz.');
        }
    
        try {
            $answers = $request->input('answers');
    
            foreach ($answers as $questionId => $answer) {
                $question = QuizQuestion::find($questionId);
                $answerOptionId = null;
    
                if ($question) {
                    if ($question->type === 'multiple_choice') {
                        $answerOptions = json_decode($question->answer_options, true);
                        $answerOptionIds = json_decode($question->answer_option_id, true);
                        $optionIndex = array_search($answer, $answerOptions);
                        $answerOptionId = $optionIndex !== false ? [$answerOptionIds[$optionIndex]] : null;
                    } elseif ($question->type === 'checkbox') {
                        $answerOptions = json_decode($question->answer_options, true);
                        $answerOptionIds = json_decode($question->answer_option_id, true);
                        $selectedOptionIds = [];
                        foreach ($answer as $selectedOption) {
                            $optionIndex = array_search($selectedOption, $answerOptions);
                            if ($optionIndex !== false) {
                                $selectedOptionIds[] = $answerOptionIds[$optionIndex];
                            }
                        }
                        $answerOptionId = $selectedOptionIds;
                    }
                }
    
                UserResponse::updateOrCreate([
                    'user_id' => $user->id,
                    'quiz_question_id' => $questionId,
                ], [
                    'answer' => is_array($answer) ? json_encode($answer) : $answer,
                    'user_answer_option_id' => $answerOptionId ? json_encode($answerOptionId) : null,
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

   // Admin view employee quiz answer 
    public function viewQuizList()
    {
        $user = Auth::user();
        $companyId = $user->companyUser->CompanyID; // Assuming the user model has a relationship to the company
        $quizzes = Quiz::where('company_id', $companyId)->get();

        return view('admin.view-quiz-list', compact('quizzes'));
    }

    public function viewAnsweredEmployees(Quiz $quiz)
    {
        $user = Auth::user();
        $companyId = $user->companyUser->CompanyID;

        if ($quiz->company_id !== $companyId) {
            return redirect()->route('admin.view-quiz-list')->with('error', 'You do not have permission to view this quiz.');
        }

        $employees = User::whereHas('quizAttempts', function ($query) use ($quiz) {
            $query->where('quiz_id', $quiz->id);
        })->whereHas('companyUser', function ($query) use ($companyId) {
            $query->where('CompanyID', $companyId);
        })->get();

        return view('admin.employee-quiz-list', compact('quiz', 'employees'));
    }


    
    public function viewQuizAnswer(Quiz $quiz, User $employee)
    {
        $userResponses = UserResponse::where('user_id', $employee->id)
            ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
            ->get()
            ->keyBy('quiz_question_id');
    
        $correctAnswers = DB::table('employee_correct_answers')
            ->where('user_id', $employee->id)
            ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
            ->get()
            ->pluck('correct_answer', 'quiz_question_id')
            ->map(function ($item) {
                return json_decode($item, true);
            })
            ->toArray();
    
        // Calculate total result
        $totalResult = DB::table('employee_correct_answers')
            ->where('user_id', $employee->id)
            ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
            ->sum('result');
    
        $totalQuestions = count($quiz->questions);
    
        return view('admin.view-quiz-answer', compact('quiz', 'employee', 'userResponses', 'correctAnswers', 'totalResult', 'totalQuestions'));
    }
    


    public function updateQuizAnswer(Request $request, Quiz $quiz, User $employee)
    {
        $correctAnswers = json_decode($request->input('correct_answers_json'), true);
        $totalResult = 0;
        $totalQuestions = count($quiz->questions);
    
        foreach ($correctAnswers as $questionId => $correctAnswer) {
            $question = QuizQuestion::find($questionId);
            if ($question) {
                // Ensure correctAnswer is an array
                if (!is_array($correctAnswer)) {
                    $correctAnswer = [$correctAnswer];
                }
    
                // Remove null values and reindex the array
                $correctAnswer = array_values(array_filter($correctAnswer, function($value) {
                    return !is_null($value);
                }));
    
                // Calculate the result
                $userResponse = UserResponse::where('user_id', $employee->id)
                    ->where('quiz_question_id', $questionId)
                    ->first();
    
                $result = 0;
                if ($userResponse) {
                    if ($question->type === 'multiple_choice') {
                        // Compare the answer option ID
                        $userAnswerOptionId = $userResponse->answer_option_id ? json_decode($userResponse->answer_option_id, true) : [];
                        if (isset($userAnswerOptionId[0]) && $userAnswerOptionId[0] === $correctAnswer[0]) {
                            $result = 1;
                        }
                    } elseif ($question->type === 'short_answer') {
                        if (strtolower($userResponse->answer) === strtolower($correctAnswer[0])) {
                            $result = 1;
                        }
                    } elseif ($question->type === 'checkbox') {
                        $userAnswers = is_string($userResponse->answer_option_id) ? json_decode($userResponse->answer_option_id, true) : $userResponse->answer_option_id;
                        if (is_array($userAnswers) && count($userAnswers) === count($correctAnswer) && !array_diff($userAnswers, $correctAnswer)) {
                            $result = 1;
                        }
                    }
                }
    
                $totalResult += $result;
    
                // Save the correct answer and result for this specific employee
                DB::table('employee_correct_answers')->updateOrInsert(
                    [
                        'user_id' => $employee->id,
                        'quiz_question_id' => $questionId
                    ],
                    [
                        'correct_answer' => json_encode($correctAnswer),
                        'result' => $result,
                        'updated_at' => now()
                    ]
                );
            }
        }
    
        return redirect()->route('admin.view-quiz-answer', ['quiz' => $quiz->id, 'employee' => $employee->id])
            ->with('success', 'Correct answers updated successfully.')
            ->with('totalResult', $totalResult)
            ->with('totalQuestions', $totalQuestions);
    }
    


}

