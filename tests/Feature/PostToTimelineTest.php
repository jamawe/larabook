<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    // Refresh database after every test
    use RefreshDatabase;

    /** @test */

    public function a_user_can_post_a_text_post() {

        // In order to associate a post with a user they need to be logged in
        // Create user on-the-fly and authenticate them as current user
        // Second param: api should get authenticated

        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => 'Testing Body',
                ]
            ]
        ]);

        // If post was successfully created it must exist in database
        $post = Post::first();

        $response->assertStatus(201);
    }
}
