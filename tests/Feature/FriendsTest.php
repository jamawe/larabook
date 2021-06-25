<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_send_a_friend_request()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');
        $anotherUser = User::factory()->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        // Is there in fact a record of a request?
        $friendRequest = \App\Models\Friend::first();

        $this->assertNotNull($friendRequest);

        // Do id of anotherUser and id from friendRequest match/ an the ids of the auth user resp.?
        $this->assertEquals($anotherUser->id, $friendRequest->friend_id);
        $this->assertEquals($user->id, $friendRequest->user_id);

        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => null,
                ]
            ],
            'links' => [
                'self' => url('/users/'.$anotherUser->id),
            ]
        ]);
    }

    /** @test */
    public function only_valid_users_can_be_friend_requested()
    {

        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => 123, // Use id which doesn't exist
        ])->assertStatus(404);

        $this->assertNull(Friend::first());

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'User Not Found',
                'detail'=> 'Unable to locate the user with the given information.'
            ]
        ]);
    }

}
