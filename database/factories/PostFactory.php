<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence;

        return [
            'title' => $title,
            // 'slug' => $this->faker->slug,
           'slug' => Str::slug($title),
            // 'category_id' => $this->faker->numberBetween(1, 10),
            'category_id' => Category::factory(),
            'user_id' => User::factory(), // Assigns a user to the post
            'content' => $this->faker->text,
            'published_at' => $this->faker->dateTime->format('Y-m-d H:i:s'),
        ];
    }
}
