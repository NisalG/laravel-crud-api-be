<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\Post;
use Database\Seeders\PostsTableSeeder;
use Carbon\Carbon;
use Mockery;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;

class PostsTableSeederTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Log::spy();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that the seeder handles a scenario where the CSV file is missing.
     *
     * @return void
     */
    public function testMissingCsvFile()
    {
        $storageMock = Mockery::mock('alias:Illuminate\Support\Facades\Storage');
        $storageMock->shouldReceive('exists')
            ->with('/storage/app/faqs.csv')
            ->andReturn(false); // File not exists

        // Create a mock instance of the seeder
        $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

        // Mock the getCsvData method to return an empty array
        $seeder->shouldReceive('getCsvData')->andReturn([]);

        // Run the seeder
        $seeder->run();

        // Assertions
        // Ensure no records are created
        $this->assertCount(0, Post::all());
    }

    /**
     * Test that the seeder handles an empty CSV file gracefully.
     *
     * @return void
     */
    public function testEmptyCsvFile()
    {
        $storageMock = Mockery::mock('alias:Illuminate\Support\Facades\Storage');
        $storageMock->shouldReceive('exists')
            ->with('/storage/app/faqs.csv')
            ->andReturn(true); // File exists

        // Create a mock instance of the seeder
        $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

        // Mock the getCsvData method to return an empty array
        $seeder->shouldReceive('getCsvData')->andReturn([]);

        // Run the seeder
        $seeder->run();

        // Assertions
        // Ensure no records are created
        $this->assertCount(0, Post::all());
    }

    /**
     * Test that the seeder inserts data from a valid CSV file into the database.
     *
     * @return void
     */
    public function testValidCsvData()
    {
        $category =Category::factory()->create();
        $user = User::factory()->create();

        $csvData = [
            ['id' => 1, 'title' => 'Test Post 1', 'slug' => Str::slug('Test Post 1'), 'content' => 'This is a test post', 'published' => false, 'published_at' => Carbon::now()->toDateTimeString(), 'user_id' => $user->id, 'category_id' => $category->id],
            ['id' => 2, 'title' => 'Test Post 2', 'slug' => Str::slug('Test Post 2'), 'content' => 'This is another test post', 'published' => false, 'published_at' => Carbon::now()->toDateTimeString(), 'user_id' => $user->id, 'category_id' => $category->id],
        ];

        // Create a mock instance of the seeder
        $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

        // Mock the getCsvData method to return above CSV data
        $seeder->shouldReceive('getCsvData')->andReturn($csvData);

        // Run the seeder
        $seeder->run();

        // Assertions
        // Ensure one record is created
        $this->assertCount(2, Post::all());
    }

    /**
     * Test that the seeder handles rows with a non-numeric ID value.
     *
     * @return void
     */
    public function testInvalidCsvDataNonNumericId()
    {
        $category =Category::factory()->create();
        $user = User::factory()->create();

        $csvData = [
            ['id' => 'abc', 'title' => 'Test Post 1', 'content' => 'This is a test post'],
            [
                'id' => 200,
                'title' => 'Test Post 2',
                'slug' => Str::slug('Test Post 1'),
                'content' => 'This is another test post',
                'published' => false,
                'published_at' => Carbon::now()->toDateTimeString(),
                'user_id' => $user->id, 'category_id' => $category->id
            ],
        ];

        // Create a mock instance of the seeder
        $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

        // Mock the getCsvData method to return above CSV data
        $seeder->shouldReceive('getCsvData')->andReturn($csvData);

        // Run the seeder
        $seeder->run();

        // Assertions
        // Ensure one record is created
        $this->assertCount(1, Post::all());
    }

    /**
     * Test that the seeder handles rows with a duplicate slug.
     *
     * @return void
     */
    public function testDuplicateSlug()
    {
        $category =Category::factory()->create();
        $user = User::factory()->create();

        $csvData = [
            ['id' => 1, 'title' => 'Test Post', 'slug' => Str::slug('Test Post 1'), 'content' => 'This is a test post', 'published' => false, 'published_at' => Carbon::now()->toDateTimeString(), 'user_id' => $user->id, 'category_id' => $category->id],
            ['id' => 2, 'title' => 'Another Test Post', 'slug' => Str::slug('Test Post 1'), 'content' => 'This is another test post', 'published' => false, 'published_at' => Carbon::now()->toDateTimeString(), 'user_id' => $user->id, 'category_id' => $category->id],
        ];

        // Create a mock instance of the seeder
        $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

        // Mock the getCsvData method to return above CSV data
        $seeder->shouldReceive('getCsvData')->andReturn($csvData);

        // Run the seeder
        $seeder->run();
    
        // Assertions
        // Ensure only one record is created due to the unique slug constraint
        $this->assertCount(1, Post::all());
    
        // Additionally, verify the inserted record's slug matches the expected one
        $this->assertEquals('test-post-1', Post::first()->slug);
    }

    /**
     * Test that the seeder handles rows with an empty content field.
     *
     * @return void
     */
    public function testEmptyContentField()
    {
        $category =Category::factory()->create();
        $user = User::factory()->create();

        $csvData = [
            ['id' => 1, 'title' => 'Test Post', 'slug' => Str::slug('Test Post 1'), 'content' => '', 'published' => false, 'published_at' => Carbon::now()->toDateTimeString(), 'user_id' => $user->id, 'category_id' => $category->id],
        ];

        // Create a mock instance of the seeder
        $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

        // Mock the getCsvData method to return above CSV data
        $seeder->shouldReceive('getCsvData')->andReturn($csvData);

        // Run the seeder
        $seeder->run();

        $post = Post::first();
        $this->assertEquals('', $post->content);
    }

    /**
     * Test that the seeder handles rows with invalid date format in `created_at` or `updated_at`.
     *
     * @return void
     */
    // public function testInvalidDateFormat()
    // {
    //     $csvData = [
    //         ['id' => 1, 'title' => 'Test Post', 'slug' => Str::slug('Test Post 1'), 'content' => 'This is a test post',  'published' => false,'published_at' => Carbon::now()->toDateTimeString(), 'created_at' => 'invalid_date_format'],
    //     ];

    //     // Create a mock instance of the seeder
    //     $seeder = Mockery::mock(PostsTableSeeder::class)->makePartial();

    //     // Mock the getCsvData method to return above CSV data
    //     $seeder->shouldReceive('getCsvData')->andReturn($csvData);

    //     // Run the seeder
    //     $seeder->run();

    //     $post = Post::first();
    //     $this->assertNull($post->created_at);
    // }
    public function testInvalidDateFormat()
    {
        $category =Category::factory()->create();
        $user = User::factory()->create();

        $csvData = [
            [
                'id' => 1,
                'title' => 'Test Post',
                'slug' => Str::slug('Test Post 1'),
                'content' => 'This is a test post',
                'published' => false,
                'published_at' => Carbon::now()->toDateTimeString(),
                'created_at' => 'invalid_date_format',
                'user_id' => $user->id, 'category_id' => $category->id
            ],
        ];

        // Create a mock instance of the seeder
        $seeder = $this->getMockBuilder(PostsTableSeeder::class)
            ->onlyMethods(['getCsvData'])
            ->getMock();

        // Mock the getCsvData method to return above CSV data
        $seeder->expects($this->once())
            ->method('getCsvData')
            ->willReturn($csvData);

        // Run the seeder
        $seeder->run();

        $post = Post::first();
        // Assert that the created_at field is not null since it is handled in the seeder
        $this->assertNotNull($post->created_at);
    }
}
