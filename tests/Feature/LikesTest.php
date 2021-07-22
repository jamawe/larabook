<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_like_a_post()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');
        // Create post with certain id (not 1), so the ids won't get confused with each other
        $post = Post::factory()->create(['id' => 123]);

        $response = $this->post('/api/posts/'.$post->id.'/like')
            ->assertStatus(200);

        // For fetching all of the liked posts of the authUser a count of 1 is expected
        $this->assertCount(1, $user->likedPosts);
        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'likes',
                        'like_id' => 1,
                        'attributes' => []
                    ],
                    'links' => [
                        'self' => url('/posts/123'),
                    ]
                ]
            ],
            'links' => [
                'self' => url('/posts'),
            ]
        ]);
    }
}
