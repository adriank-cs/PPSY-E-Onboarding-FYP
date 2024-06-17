<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Answer;
use App\Models\PostHistory;
use App\Models\AnswerHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    // HOMEPAGE
    public function homepageName()
    {
        // Retrieve posts with associated user IDs, excluding deleted posts
        $randomPosts = Post::whereNull('deleted_at')->inRandomOrder()->limit(4)->get();
    
        // Fetch user names from the database
        $userIds = $randomPosts->pluck('UserID')->unique()->toArray();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();
    
        // Pass the retrieved data to the view
        return view('admin.discussion', ['randomPosts' => $randomPosts, 'users' => $users]);
    }
    

    // TYPE YOUR OWN QUESTION
    public function typeOwn()
    {
    
        // Pass the retrieved data to the view
        return view('admin.create-post');
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        \Log::info("Autocomplete query: {$query}");

        $posts = Post::where('title', 'LIKE', "%{$query}%")
                        ->get(['PostID', 'title']);

        \Log::info("Autocomplete results: " . $posts->toJson());

        return response()->json($posts);
    }

    public function getDetails()
    {
        // Retrieve the logged-in user
        $user = Auth::user();

        // Get the UserID, CompanyID, and Name
        $userID = $user->id;
        $companyID = null;
        $name = $user->name;

        // Check if the user has a CompanyUser relationship
        if ($user->companyUser) {
            $companyID = $user->companyUser->CompanyID;
        }

        // Return the user's information or use it as needed in your logic
        return [
            'UserID' => $userID,
            'CompanyID' => $companyID,
            'Name' => $name
        ];
    }

    public function createPost(Request $request)
    {
        // Retrieve user information
        $userInfo = $this->getDetails();
    
        // Retrieve title and content from the request
        $title = $request->input('title');
        $content = $request->input('content');
    
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads', 'public');
        }
    
        // Set the current system time for created_at
        $createdAt = Carbon::now();
    
        // Determine if the post should be anonymous
        $isAnonymous = $request->has('is_anonymous') ? true : false;
    
        // Create a new post entry
        $post = Post::create([
            'title' => $title,
            'content' => $content,
            'UserID' => $userInfo['UserID'], // User's ID
            'CompanyID' => $userInfo['CompanyID'], // Company's ID
            'is_answered' => false, // Set default value for is_answered
            'is_locked' => false, // Set default value for is_locked
            'is_archived' => false, // Set default value for is_archived
            'is_anonymous' => $isAnonymous, // Set default value for is_anonymous
            'created_at' => $createdAt, // Set created_at to the current system time
            'updated_at' => null, // Set updated_at to null
            'image' => $imagePath ? Storage::url($imagePath) : null
        ]);
    
        // Get the created post's PostID
        $postID = Post::where('UserID', $userInfo['UserID'])
                   ->latest()
                   ->value('PostID');
    
        $postHistory = PostHistory::create([
            'PostID' => $postID,
            'UserID' => $userInfo['UserID'], // User's ID
            'CompanyID' => $userInfo['CompanyID'], // Company's ID
            'title' => $title,
            'content' => $content,
            'is_answered' => false, // Set default value for is_answered
            'is_locked' => false, // Set default value for is_locked
            'is_archived' => false, // Set default value for is_archived
            'created_at' => $createdAt, // Set created_at to the current system time
            'updated_at' => null, // Set updated_at to null
            'deleted_at' => null, // Set deleted_at to null
            'image' => $imagePath ? Storage::url($imagePath) : null
         ]);
    
        // Optionally, you can return a response or redirect the user to a different page
        return redirect()->route('admin.postDisplay', ['PostID' => $postID]);
    }

    public function postDisplay($PostID)
    {
        // Fetch the post details including soft-deleted posts
        $post = Post::withTrashed()->where('PostID', $PostID)->firstOrFail();
        
        // Fetch answers including soft-deleted answers
        $answers = Answer::withTrashed()->where('PostID', $PostID)->get();
        
        // Get user name from db with name field
        $user = User::where('id', $post->UserID)->first(['name']);
        //get profile from user id
        $profile = Profile::where('user_id', $post->UserID)->first();
        
        // Get user names for answers
        $userIds = $answers->pluck('UserID')->unique()->toArray();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();
        
        // Pass the post details to the blade view
        return view('admin.postdisplay', compact('post', 'answers', 'user', 'users', 'profile'));
    }
    
    public function submitAnswer(Request $request, $PostID)
    {
        // Retrieve user information
        $userInfo = $this->getDetails();
    
        // Retrieve answer content and anonymity status
        $answer = $request->input('answer');
        $isAnonymous = $request->has('is_anonymous') ? true : false;
    
        // Set the current system time for created_at
        $createdAt = Carbon::now();
    
        // Create the answer
        $answerEntry = Answer::create([
            'UserID' => $userInfo['UserID'],
            'CompanyID' => $userInfo['CompanyID'],
            'PostID' => $PostID,
            'content' => $answer,
            'created_at' => $createdAt,
            'updated_at' => null,
            'is_anonymous' => $isAnonymous,
        ]);
    
        // Get the created answer's ID
        $answerID = Answer::where('UserID', $userInfo['UserID'])
                    ->latest()
                    ->value('AnswerID');
    
        // Create an entry in the answer history
        AnswerHistory::create([
            'AnswerID' => $answerID,
            'UserID' => $userInfo['UserID'],
            'CompanyID' => $userInfo['CompanyID'],
            'PostID' => $PostID,
            'content' => $answer,
            'created_at' => $createdAt,
            'updated_at' => null,
            'deleted_at' => null,
        ]);
    
        // Optionally, you can return a response or redirect the user
        return redirect()->back()->with('success', 'Your answer has been submitted.');
    }    

    public function deletePost($PostID)
    {
        $post = Post::withTrashed()->where('PostID', $PostID)->firstOrFail();
        $post->deleted_at = Carbon::now();
        $post->save();
    
        return redirect()->route('admin.check-post', ['filter' => request('filter', 'all_posts')]);
    }
        
    public function checkPostedQuestions(Request $request)
    {
        // Retrieve the logged-in user
        $user = Auth::user();
    
        // Get the company ID of the logged-in user
        $companyID = $user->companyUser->CompanyID ?? null;
    
        // Determine the filter type
        $filter = $request->query('filter', 'all_posts');
    
        if ($filter == 'my_questions') {
            // Retrieve posts that match the user's ID and company ID, including soft deleted posts
            $postedQuestions = Post::withTrashed()->where('CompanyID', $companyID)
                ->where('UserID', $user->id)
                ->withCount('answers') // Get the count of answers for each post
                ->get();
        } else {
            // Retrieve posts that match the company ID, including soft deleted posts
            $postedQuestions = Post::withTrashed()->where('CompanyID', $companyID)
                ->withCount('answers') // Get the count of answers for each post
                ->get();
        }
    
        // Fetch user names from the database
        $userIds = $postedQuestions->pluck('UserID')->unique()->toArray();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();
    
        // Pass the retrieved data to the view
        return view('admin.check-post', ['postedQuestions' => $postedQuestions, 'users' => $users]);
    }      
    
    public function viewHistory($PostID)
    {
        $postHistories = PostHistory::where('PostID', $PostID)->get();
    
        return view('admin.post-history', compact('postHistories'));
    }

    public function editPost($PostID)
    {
        // Fetch the post details including soft-deleted posts
        $post = Post::withTrashed()->where('PostID', $PostID)->firstOrFail();

        // Get user name from db with name field
        $user = User::where('id', $post->UserID)->first(['name']);

        // Pass the post details to the blade view
        return view('admin.edit-post', compact('post', 'user'));
    }

    public function updatePost(Request $request, $PostID)
    {
        // Retrieve user information
        $userInfo = $this->getDetails();

        // Retrieve title and content from the request
        $title = $request->input('title');
        $content = $request->input('content');

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads', 'public');
        }

        // Fetch the post details including soft-deleted posts
        $post = Post::withTrashed()->where('PostID', $PostID)->firstOrFail();

        // Update the post
        $post->title = $title;
        $post->content = $content;
        if ($imagePath) {
            $post->image = Storage::url($imagePath);
        }
        $post->updated_at = Carbon::now();
        $post->save();

        // Create a new entry in the post history
        PostHistory::create([
            'PostID' => $post->PostID,
            'UserID' => $userInfo['UserID'],
            'CompanyID' => $userInfo['CompanyID'],
            'title' => $title,
            'content' => $content,
            'is_answered' => $post->is_answered,
            'is_locked' => $post->is_locked,
            'is_archived' => $post->is_archived,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'deleted_at' => $post->deleted_at,
            'image' => $imagePath ? Storage::url($imagePath) : $post->image
        ]);

        // Redirect back to the post display page
        return redirect()->route('admin.postDisplay', ['PostID' => $post->PostID]);
    }

    public function editAnswer($AnswerID)
    {
        // Fetch the answer details including soft-deleted answers
        $answer = Answer::withTrashed()->where('AnswerID', $AnswerID)->firstOrFail();
        
        // Get user name from db with name field
        $user = User::where('id', $answer->UserID)->first(['name']);
        
        // Pass the answer details to the blade view
        return view('admin.edit-answer', compact('answer', 'user'));
    }
    
    public function updateAnswer(Request $request, $AnswerID)
    {
        // Retrieve user information
        $userInfo = $this->getDetails();
    
        // Retrieve content from the request
        $content = $request->input('content');
    
        // Fetch the answer details including soft-deleted answers
        $answer = Answer::withTrashed()->where('AnswerID', $AnswerID)->firstOrFail();
    
        // Update the answer
        $answer->content = $content;
        $answer->updated_at = Carbon::now();
        $answer->save();
    
        // Create a new entry in the answer history
        AnswerHistory::create([
            'AnswerID' => $answer->AnswerID,
            'UserID' => $userInfo['UserID'],
            'CompanyID' => $userInfo['CompanyID'],
            'PostID' => $answer->PostID,
            'content' => $content,
            'created_at' => $answer->created_at,
            'updated_at' => $answer->updated_at,
            'deleted_at' => $answer->deleted_at,
        ]);
    
        // Redirect back to the post display page
        return redirect()->route('admin.postDisplay', ['PostID' => $answer->PostID]);
    }
    
    public function deleteAnswer($AnswerID)
    {
        $answer = Answer::withTrashed()->where('AnswerID', $AnswerID)->firstOrFail();
        $answer->deleted_at = Carbon::now();
        $answer->save();
    
        return redirect()->back()->with('success', 'The answer has been deleted.');
    }
    
    public function viewAnswerHistory($AnswerID)
    {
        $answerHistories = AnswerHistory::where('AnswerID', $AnswerID)->get();
        
        return view('admin.answer-history', compact('answerHistories'));
    }        

    public function search(Request $request)
    {
        $query = $request->input('search');
    
        // Search for posts with titles matching the query
        $searchResults = Post::where('title', 'LIKE', "%{$query}%")->get();
    
        // Fetch user names from the database
        $userIds = $searchResults->pluck('UserID')->unique()->toArray();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();
    
        if ($request->ajax()) {
            $view = view('partials.search-results', compact('searchResults', 'users'))->render();
            return response()->json(['html' => $view]);
        }
    
        // Pass the search results and user names to the view
        return view('admin.searched', compact('searchResults', 'users'));
    }
                        
}

