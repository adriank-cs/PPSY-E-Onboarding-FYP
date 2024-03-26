<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function homepageName()
    {
        // Fetch four random posts from the database
        $randomPosts = Post::inRandomOrder()->limit(4)->get();

        // Retrieve the user IDs associated with the fetched posts
        $userIds = $randomPosts->pluck('UserID')->toArray();

        // Find the users from the users table using the retrieved user IDs
        $users = User::whereIn('id', $userIds)->pluck('name', 'id');

        // Pass the fetched posts and users to the view
        return view('discussion.homepage', ['randomPosts' => $randomPosts, 'users' => $users]);
    }
}
