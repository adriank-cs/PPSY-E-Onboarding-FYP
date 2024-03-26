<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage for image handling
use Illuminate\Support\Facades\Auth;
use App\Models\UserResponse;
use App\Models\ModuleQuestion;
use App\Http\Requests\StoreModuleRequest;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();
        return view('employee.onboarding-home-page', compact('modules')); // Return the view
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee.create-modules'); // Return the view
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|min:3',
        ]);

        $fileName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

        // Use Storage to store the image:
        $path = $request->file('image')->storeAs('modules', $fileName, 'public');

        // dump the path:
        //dd($path);

        $module = Module::create([
            'title' => $request->input('title'),
            'image_path' => $path, // Store the stored path
        ]);

        //$module = Module::create($request->validated());

        $fileName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('modules', $fileName); 

        $module->save();
        // Add questions to the module
        foreach ($request->questions as $question) {
            $moduleQuestion = new ModuleQuestion;
            $moduleQuestion->module_id = $module->id;
            $moduleQuestion->question = $question;
            $moduleQuestion->type = 'multiple_choice'; // Set default type to multiple choice (modify as needed)
            $moduleQuestion->save();
        }

        return redirect()->route('employee.onboarding-home-page')->with('success', 'Module created successfully!');
    }

    public function __invoke(Request $request)
    {
        // Logic for the route action in this case (e.g., display a welcome message)
        return view('welcome');
    }

    public function show(Module $module)
    {
        $user = auth()->user(); // Assuming you have a user authentication system
        if (auth()->check()) {
            $answeredQuestions = $user->responses()
                ->whereIn('module_question_id', $module->questions->pluck('id'))
                ->count();

            $completionPercentage = round(($answeredQuestions / $module->questions->count()) * 100);

            $userResponses = []; // Initialize an empty array to store user responses
            if ($user) {
                $userResponses = $user->responses()
                    ->whereIn('module_question_id', $module->questions->pluck('id'))
                    ->get();
            }

            return view('employee.module-details', compact('module', 'completionPercentage', 'userResponses'));
        } else {
            // Redirect to login or handle unauthenticated user scenario
            return redirect()->route('login');
        }
    }

    // New route for submitting answers 
    public function submitAnswers(Module $module, Request $request)
    {
        $user = auth()->user();

        if ($user) {
            foreach ($request->input('answers') as $questionId => $answer) {
                $userResponse = UserResponse::updateOrCreate([
                    'user_id' => $user->id,
                    'module_question_id' => $questionId,
                ], [
                    'answer' => $answer,
                ]);
            }

            // Add a success message using flash session
            session()->flash('success', 'Answers submitted successfully!');
        }

        return redirect()->route('modules.show', $module->id);
    }


}



