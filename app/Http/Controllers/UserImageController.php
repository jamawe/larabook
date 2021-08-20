<?php

namespace App\Http\Controllers;

use App\Models\UserImage;
use App\Http\Resources\UserImage as UserImageResource;

class UserImageController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'image' => '',
            'width' => '',
            'height' => '',
            'location' => '',
        ]);

        $image = $data['image']->store('user-images', 'public');

        $userImage = auth()->user()->images()->create([
            'path' => $image,
            'width' => $data['width'],
            'height' => $data['height'],
            'location' => $data['location'],
        ]);

        return new UserImageResource($userImage);
    }
}
