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
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|min:3',
            'question_types' => 'required|array|min:1',
            'question_types.*' => 'required|string|in:multiple_choice,short_answer,checkbox',
        ], [
            'questions.*.required' => 'The question field is required.',
            'questions.*.min' => 'The question field must be at least 3 characters.',
            'question_types.*.required' => 'The question type field is required.',
            'question_types.*.in' => 'The question type field must be one of: multiple_choice, short_answer, checkbox',
        ]);

        // Create a new quiz
        $quiz = Quiz::create([
            'title' => $request->input('title'),
        ]);

        // Process each question
        foreach ($request->questions as $key => $question) {
            $quizQuestion = new QuizQuestion;
            $quizQuestion->quiz_id = $quiz->id;
            $quizQuestion->question = $question;
            $quizQuestion->type = $request->input('question_types')[$key];

            // Handle answer options for multiple choice and checkbox question types
            if (in_array($quizQuestion->type, ['multiple_choice', 'checkbox'])) {
                $answerOptions = $request->input('answers')[$key] ?? [];
                if (!empty($answerOptions)) {
                    $quizQuestion->answer_options = json_encode($answerOptions);
                }
            }

            // Save the quiz question
            $quizQuestion->save();
        }

        return redirect()->route('employee.onboarding-quiz')->with('success', 'Quiz created successfully!');
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