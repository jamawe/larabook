<?php

namespace App\Http\Controllers;

use App\Exceptions\FriendRequestNotFoundException;
use App\Http\Resources\Friend as FriendResources;
use App\Models\Friend;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FriendRequestResponseController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'user_id' => '',
            'status' => '',
        ]);

        try {
            $friendRequest = Friend::where('user_id', $data['user_id'])
            ->where('friend_id', auth()->user()->id) // the only one who can accept a friend request is the one getting the request - only_the_recipient_can_accept_a_friend_request
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new FriendRequestNotFoundException();
        }
        
        $friendRequest->update(array_merge($data, [
            'confirmed_at' => now(),
        ]));

        return new FriendResources($friendRequest);
    }
}
