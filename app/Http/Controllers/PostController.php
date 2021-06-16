<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store() {

        $data = request()->validate([
            'data.attributes.body' => '',
        ]);

        // Bc the relationship not yet exists Post::create not possible, but request()->user()->posts()->create($data) works
        $post = request()->user()->posts()->create($data['data']['attributes']);
        
        return response([

            'data' => [
                'type' => 'posts',
                'post_id' => $post->id,
                'attributes' => [
                    'body' => $post->body,
                ],
            ],
            'links' => [
                'self' => url('/posts/'.$post->id),
            ]

        ], 201);
    }
}
