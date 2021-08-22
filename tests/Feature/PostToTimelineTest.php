<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    // Refresh database after every test
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /** @test */

    public function a_user_can_post_a_text_post() {

        // In order to associate a post with a user they need to be logged in
        // Create user on-the-fly and authenticate them as current user
        // Second param: api should get authenticated

        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');

        // Properly formatted request
        $response = $this->post('/api/posts', [
                    'body' => 'Testing Body',
        ]);

        // If post was successfully created it must exist in database
        $post = Post::first();

        // Assert 1 if all of the posts are fetched (check if there actually is a post)
        $this->assertCount(1, Post::all()); // Test result: Failed asserting that actual size 0 matches expected size 1. -- no post

        // Define what is expected in the response
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('Testing Body', $post->body);
        $response->assertStatus(201)
            ->assertJson([ // Properly formatted response
                'data' => [
                    'type' => 'posts',
                    'post_id' => $post->id,
                    'attributes' => [
                        'posted_by' => [
                            'data' => [
                                'attributes' => [
                                    'name' => $user->name,
                                ]
                            ]
                        ],
                        'body' => 'Testing Body',
                    ],
                ],
                'links' => [
                    'self' => url('/posts/'.$post->id),
                ]
            ]);
    }

    /** @test */

    public function a_user_can_post_a_text_post_with_an_image() {

        // In order to associate a post with a user they need to be logged in
        // Create user on-the-fly and authenticate them as current user
        // Second param: api should get authenticated

        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');

        $file = UploadedFile::fake()->image('user-post.jpg');

        // Properly formatted request
        $response = $this->post('/api/posts', [
                    'body' => 'Testing Body',
                    'image' => $file,
                    'width' => 100,
                    'height' => 100,
        ]);

        Storage::disk('public')->assertExists('post-images/'.$file->hashName());
        $response->assertStatus(201)
            ->assertJson([ // Properly formatted response
                'data' => [
                    'attributes' => [
                        'body' => 'Testing Body',
                        'image' => url('post-images/'.$file->hashName()),
                    ],
                ],
            ]);
    }
}
