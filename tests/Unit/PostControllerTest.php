<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        // Log::spy();

        // Create a user (or use factory to create one)
        $user = User::factory()->create();

        // Acting as the user for authenticated requests
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        // Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that the index action list all posts
     *
     * @return void
     */
    #[Test]
    public function it_can_list_all_posts()
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /**
     * Test that the store action store a new post
     *
     * @return void
     */
    #[Test]
    public function it_can_store_a_new_post()
    {
        $postData = [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'category_id' => $this->faker->numberBetween(1, 10),
            'content' => $this->faker->paragraph,
            'published_at' => $this->faker->date,
        ];

        $response = $this->postJson('/api/v2/posts', $postData);

        if ($response->status() === 422) {
            // Output the validation errors
            $errors = $response->json('errors');
            $this->fail('Validation failed: ' . json_encode($errors, JSON_PRETTY_PRINT));
        }

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => $postData['title'],
                'slug' => $postData['slug'],
                'category_id' => $postData['category_id'],
                'content' => $postData['content'],
                'published_at' => $postData['published_at'],
            ]);
    }

    /**
     * Test that the show action show a post by id
     *
     * @return void
     */
    #[Test]
    public function it_can_show_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/v2/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'category_id' => $post->category_id,
                'content' => $post->content,
            ]);
    }

    /**
     * Test that the update action updates a post by id
     *
     * @return void
     */
    #[Test]
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create();

        $updatedData = [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'category_id' => $this->faker->numberBetween(1, 10),
            'content' => $this->faker->paragraph,
            'published_at' => $this->faker->date,
        ];

        $response = $this->putJson("/api/v2/posts/{$post->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'title' => $updatedData['title'],
                'slug' => $updatedData['slug'],
                'content' => $updatedData['content'],
                'published_at' => $updatedData['published_at'],
            ]);
    }

    /**
     * Test that the delete action deletes a post by id
     *
     * @return void
     */
    #[Test]
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/v2/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Post deleted successfully']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /**
     * Test that the search action search a post/faq
     *
     * @return void
     */
    #[Test]
    public function it_can_search_answers_by_faq()
    {
        Post::factory()->create(['content' => 'This is a sample FAQ answer.']);

        $response = $this->getJson('/api/v2/posts/answers?faq="sample"');

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'This is a sample FAQ answer.']);
    }
}
