<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RetrievePostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_retrieve_posts()
    {

        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');
        $posts = Post::factory()->count(2)->create();

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [ // $posts (is an array)
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->first()->id,
                            'attributes' => [
                                'body' => $posts->first()->body,
                            ]
                        ]
                    ],

                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->last()->id,
                            'attributes' => [
                                'body' => $posts->last()->body,
                            ]
                        ]
                    ],
                ],
                'links' => [
                    'self' => url('/posts'),
                ]
            ]);
    }
}
