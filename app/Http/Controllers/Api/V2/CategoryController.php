<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Eager load categories with their posts in one query
    // The query is executed only once, when the first category is fetched.
    // Solves the N+1 query problem by loading all related models in one query. This is generally more efficient when you need related models for multiple parent models.

    public function getCategoriesWithPostsEager()
    {
        // Fetch all categories with their posts eagerly loaded
        $categories = Category::with('posts')->get();

        // Posts are already loaded, so no additional queries are executed
        foreach ($categories as $category) {
            $posts = $category->posts;

            // Do something with the posts
            foreach ($posts as $post) {
                echo $post->title . '<br>';
            }
        }

        return view('categories.index', compact('categories'));
    }

    // Lazy load categories then their posts
    // Suppose you want to fetch all categories and later access their related posts.
    // All categories are fetched with $categories = Category::all();.
    // When $category->posts is accessed within the loop, Laravel will lazy load the related posts for each category.
    // Can lead to the N+1 query problem, where N additional queries are executed for each related model. In the above lazy loading example, if you have 10 categories, you will end up with 11 queries (1 query for categories and 1 for each category's posts).

    public function getCategoriesWithPostsLazy()
    {
        // Fetch all categories
        $categories = Category::all();

        // Lazy load posts for each category
        foreach ($categories as $category) {
            // Access posts, which triggers lazy loading
            $posts = $category->posts;

            // Do something with the posts
            foreach ($posts as $post) {
                echo $post->title . '<br>';
            }
        }

        return view('categories.index', compact('categories'));
    }

    // Subqueries: Get categories with the count of posts.
    public function getCategoriesWithPostsCount()
    {
        $categories = Category::withCount('posts')->get();
    }
}
