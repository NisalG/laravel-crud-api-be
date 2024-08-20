<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        // Log::spy();

        // Run all migrations before each test - enable this only if required
        // $this->artisan('migrate');

        // Create a category to ensure the foreign key relationship works
        // Category::factory()->create();

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
        // $this->assertCount(3, Post::all()); // Check that 3 posts were created - gets true

        $response = $this->getJson('/api/v2/posts');
        Log::info($response->getContent()); // Log the response content for debugging - gets 3

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
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
        // dd($post);

        $response = $this->getJson("/api/v2/posts/{$post->id}");
        // dd($response->getContent());

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
     * Test that the store action store a new post
     *
     * @return void
     */
    #[Test]
    public function it_can_store_a_new_post()
    {
        $postData = Post::factory()->make()->toArray();

        // Ensure the user exists
        $postData['user_id'] = User::factory()->create()->id;

        $response = $this->postJson('/api/v2/posts', $postData);
        // dd($response->getContent());

        if ($response->status() === 422) {
            // Output the validation errors
            // dd($response->json('errors'));
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


        // Optionally, verify the post exists in the database
        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'slug' => $postData['slug'],
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

        $title = $this->faker->sentence;

        $updatedData = [
            'title' => $title,
            'slug' => Str::slug($title),
            'category_id' => Category::factory()->create()->id,
            'content' => $this->faker->paragraph,
            'published_at' => $this->faker->dateTime->format('Y-m-d H:i:s'),
        ];

        $response = $this->putJson("/api/v2/posts/{$post->id}", $updatedData);
        // dd($response->getContent());

        if ($response->status() === 422) {
            // Output the validation errors
            // dd($response->json('errors'));
            $errors = $response->json('errors');
            $this->fail('Validation failed: ' . json_encode($errors, JSON_PRETTY_PRINT));
        }

        // Assertion to account for the data structure returned by the API
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'title' => strtoupper($updatedData['title']), // Assuming title is being returned in uppercase
                    'slug' => $updatedData['slug'],
                    'category_id' => $updatedData['category_id'],
                    'content' => $updatedData['content'],
                    'published_at' => $updatedData['published_at'],
                ],
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
        /**
         * Important:
         * Since default is `sqlite`, FullText index will not work and `app\Http\Controllers\Api\V2\PostController.php` > `getAnswers()` query testing will not work as expected
         * Check if fultext is anabled in `content` column. If empty that means not enabled:
         * `info('index: ' , DB::select('SHOW INDEX FROM posts WHERE Key_name = "content"'));`

         * If you do a basic query like this, it will work.
         * $results = DB::table('posts')
         * ->select('content')
         * ->where('content', 'LIKE', '%' . $faq . '%')
         * ->get();
         * Therefore this test will not pass with our test DB settings right now.
         */
        Post::factory()->create(['content' => 'This is a sample FAQ answer.']);

        $response = $this->getJson('/api/v2/posts/answers?faq=sample');
        // info($response->getContent());

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'This is a sample FAQ answer.']);
    }
}
