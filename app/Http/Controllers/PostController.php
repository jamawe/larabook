<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;
use App\Models\Friend;

class PostController extends Controller
{

    public function index()
    {
        // Get all of user's friends
        $friends = Friend::friendships();
        
        // If authUser has no friends
        if ($friends->isEmpty()) {
            return new PostCollection(request()->user()->posts);
        }

        // Get posts where the owner of the posts (post->user_id) is either a friend that has sent the friend request (user_id) or that authUser has sent a request (friend_id) -- filter through Posts with authUser's friends collection
        return new PostCollection(
            Post::whereIn('user_id', [$friends->pluck('user_id'), $friends->pluck('friend_id')])
            ->get()
        );

    }

    public function store() {

        $data = request()->validate([
            'body' => '',
        ]);

        // Bc the relationship not yet exists Post::create not possible, but request()->user()->posts()->create($data) works
        $post = request()->user()->posts()->create($data);
        
        // Will return 201 directly
        return new PostResource($post);
    }
}
