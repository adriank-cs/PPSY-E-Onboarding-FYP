<?php
/*  QuizController.php */
namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\UserResponse;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::all();
        return view('employee.onboarding-quiz', compact('quizzes')); // Return the view
    }


    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee.create-quiz'); // Return the view
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|min:3',
            'question_types' => 'required|array|min:1', // Add validation for question_types
            'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
            function ($validator, $field, $value, $parameters) use ($request) {
                $questions = $request->input('questions');
                $questionTypes = $request->input('question_types');
                $questionIndex = array_search($field, $parameters);
                if ($questionTypes[$questionIndex] === 'multiple_choice' && count($questions[$questionIndex]) === 0) {
                    $validator->addRule($field, 'required|array|min:1', ['min' => 2]); // Require at least 2 options for multiple choice
                }
            },
        ],[
            'questions.*.required' => 'The question field is required.',
            'questions.*.min' => 'The question field must be at least 3 characters.',
            'question_types.*.required' => 'The question type field is required.',
            'question_types.*.in' => 'The question type field must be one of: multiple_choice, short_answer, checkbox',
            'answers.*.required' => 'The answer field is required.',

            'answers.*.array' => 'The answer field must be an array.',
            'answers.*.min' => 'The answer field must have at least one option.',
        ]);


        // dump the request data:
        //dd($request->all());

        // Debugging a specific key from the request
        //$answers = $request->input('answers');
        //dd($answers); // This will dump the contents of the 'answers' key

        $quiz = Quiz::create([
            'title' => $request->input('title'),
        ]);

        $quiz->save();

        $allAnswerOptions = [];

        foreach ($request->questions as $key => $question) {
            $quizQuestion = new QuizQuestion;
            $quizQuestion->quiz_id = $quiz->id;
            $quizQuestion->question = $question;
            $quizQuestion->type = $request->input('question_types')[$key]; // Access type using index

            // Check if answer options exist for the current question (multiple choice only)
            if ($request->input('question_types')[$key] === 'multiple_choice') {
                $answerOptions = $request->input('answers')[$key] ?? []; // Use nullish coalescing for empty array

                // Ensure answer options exist before encoding and saving
                if (!empty($answerOptions)) {
                    $quizQuestion->answer_options = json_encode($answerOptions);
                }
            }

            $quizQuestion->save(); // Save the updated quiz question with answer options (if applicable)
        }
        // Set answer options for all questions after saving individual questions
        if (!empty($allAnswerOptions)) {
            $quiz->answer_options = json_encode($allAnswerOptions);
            $quiz->save();
        }
        //return response()->view('employee.create-quiz', compact('key')); // Pass $key to the view

        return redirect()->route('employee.onboarding-quiz', compact('key'))->with('success', 'Quiz created successfully!');
    }

    public function __invoke(Request $request)
    {
        // Logic for the route action in this case (e.g., display a welcome message)
        return view('welcome');
    }

    public function show(Quiz $quiz)
    {
        $user = auth()->user();

        if (auth()->check()) {
            $userResponses = $user->responses()
                ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
                ->get()
                ->keyBy('quiz_question_id'); // Key by question ID for easier lookup

            $answeredQuestions = $userResponses->count();
            $completionPercentage = $quiz->questions->count() > 0
                ? round(($answeredQuestions / $quiz->questions->count()) * 100)
                : 0;

            $completed = session()->get('quiz_completed_' . $quiz->id, false);

            return view('employee.quiz-details', compact('quiz', 'completionPercentage', 'userResponses', 'completed'));
        } else {
            return redirect()->route('login');
        }
    }

    public function submitAnswers(Quiz $quiz, Request $request)
    {
        $user = auth()->user();

        if ($user) {
            try {
                $answers = $request->input('answers'); // Get the answers array from the request

                foreach ($answers as $questionId => $answer) {
                    UserResponse::updateOrCreate([
                        'user_id' => $user->id,
                        'quiz_question_id' => $questionId,
                    ], [
                        'answer' => $answer,
                    ]);
                }

                session()->put('quiz_completed_' . $quiz->id, true); // Mark quiz as completed
                $request->session()->flash('success', 'Answers submitted successfully!');
                return redirect()->route('quizzes.show', $quiz->id);
            } catch (\Exception $e) {
                report($e);
                return abort(500, 'Error submitting answers.');
            }
        }
        return abort(401);
    }


    public function getDetails(Quiz $quiz)
    {
        $user = auth()->user();

        if (auth()->check()) {
            if ($user) {
                $userResponses = $user->responses()
                    ->with('question.quiz')
                    ->where('user_id', $user->id)
                    ->whereIn('quiz_question_id', $quiz->questions->pluck('id'))
                    ->get();

                $formattedAnswers = [];
                foreach ($userResponses as $response) {
                    if ($response->question) {
                        $question = $response->question;
                        $answerArray = unserialize($response->answer); // Unserialize answers

                        $formattedAnswers[$question->id] = $answerArray;
                    } else {
                        // Handle missing question relationship (optional)
                    }
                }

                return view('employee.view-responses', compact('quiz', 'formattedAnswers'));
            } else {
                return abort(401); // Unauthorized
            }
        } else {
            return abort(401); // Unauthorized
        }
    }


}