<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
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
        // Retrieve posts with associated user IDs
        $randomPosts = Post::inRandomOrder()->limit(4)->get();

        // Fetch user names from the database
        $userIds = $randomPosts->pluck('UserID')->unique()->toArray();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id')->toArray();

        // Pass the retrieved data to the view
        return view('discussion.homepage', ['randomPosts' => $randomPosts, 'users' => $users]);
    }

    // TYPE YOUR OWN QUESTION
    public function typeOwn()
    {
        // Pass the retrieved data to the view
        return view('discussion.typeown');
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
    
        // Set the current system time for created_at
        $createdAt = Carbon::now();
    
        // Create a new post entry
        $post = Post::create([
            'title' => $title,
            'content' => $content,
            'UserID' => $userInfo['UserID'], // User's ID
            'CompanyID' => $userInfo['CompanyID'], // Company's ID
            'is_answered' => false, // Set default value for is_answered
            'is_locked' => false, // Set default value for is_locked
            'is_archived' => false, // Set default value for is_archived
            'is_anonymous' => false, // Set default value for is_anonymous
            'created_at' => $createdAt, // Set created_at to the current system time
            'updated_at' => null // Set updated_at to null
        ]);
    
        // Get the created post's PostID
        $postID = Post::where('UserID', $userInfo['UserID'])
                   ->latest()
                   ->value('PostID');

        $postHistory = Post::create([
            
            'title' => $title,
            'content' => $content,
            'UserID' => $userInfo['UserID'], // User's ID
            'CompanyID' => $userInfo['CompanyID'], // Company's ID
            'is_answered' => false, // Set default value for is_answered
            'is_locked' => false, // Set default value for is_locked
            'is_archived' => false, // Set default value for is_archived
            'is_anonymous' => false, // Set default value for is_anonymous
            'created_at' => $createdAt, // Set created_at to the current system time
            'updated_at' => null // Set updated_at to null
         ]);

        // Optionally, you can return a response or redirect the user to a different page
        return redirect()->route('discussion.postDisplay', ['PostID' => $postID]);
    }
    
    public function postDisplay($PostID)
    {
    // Fetch the post details
    $post = Post::where('PostID', $PostID)
                //  ->where('UserID', $UserID)
                //  ->where('CompanyID', $CompanyID)
                 ->firstOrFail();
    
    // Pass the post details to the blade view
    return view('discussion.postdisplay', compact('post'));
    }
}
