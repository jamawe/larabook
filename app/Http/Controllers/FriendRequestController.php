<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use App\Http\Resources\Friend as FriendResource;

class FriendRequestController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'friend_id' => '',
        ]);

        // Find user that should get friend request (user with id of friend_id)
        // Use friends() relationship (from User model) to attach the auth user
        User::find($data['friend_id'])
            ->friends()->attach(auth()->user());

        return new FriendResource(
            Friend::where('user_id', auth()->user()->id)
                ->where('friend_id', $data['friend_id'])
                ->first() // Getting model, not collection
        );
    }
}
