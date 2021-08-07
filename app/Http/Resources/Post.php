<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
 

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'posts',
                'post_id' => $this->id, // Model properties can be accessed through $this
                'attributes' => [
                    'posted_by' => new UserResource($this->user),
                    'likes' => new LikeCollection($this->likes),
                    'comments' => new CommentCollection($this->comments),
                    'body' => $this->body,
                    'image' => $this->image,
                    'posted_at' => $this->created_at->diffForHumans(),
                ],
            ],
            'links' => [
                'self' => url('/posts/'.$this->id),
            ]
        ];
    }
}
