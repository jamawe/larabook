<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;

class PostController extends Controller
{

    public function index()
    {
        return new PostCollection(request()->user()->posts);
        // return new PostCollection(Post::all());
    }

    public function store() {

        $data = request()->validate([
            'data.attributes.body' => '',
        ]);

        // Bc the relationship not yet exists Post::create not possible, but request()->user()->posts()->create($data) works
        $post = request()->user()->posts()->create($data['data']['attributes']);
        
        // Will return 201 directly
        return new PostResource($post);
    }
}
