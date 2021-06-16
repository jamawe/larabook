<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;

class PostController extends Controller
{
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
