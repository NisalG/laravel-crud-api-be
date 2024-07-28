<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use App\Exceptions\AuthorizationException;
use App\Exceptions\DatabaseException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ValidationException;

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new EntityNotFoundException('Category');
        }

        return response()->json($category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        try {
            $category = Category::create($request->all());
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to create category");
        }

        return response()->json($category, 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new EntityNotFoundException('Category');
        }

        // Check if the user is authorized to delete the category
        // This will work only after Authorization is developed
        // if (Auth::user()->cannot('delete', $category)) {
        //     throw new AuthorizationException("You are not authorized to delete this category");
        // }

        try {
            $category->delete();
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to delete category");
        }

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

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

    // Polymorphic Relationship usage sample - Adding a comment to a category.
    // Also see PostController >> addPostComment - Adding a comment to a post.
    public function addCategoryComment(Request $request)
    {
        $category = Category::find($request->category_id);
        $category->comments()->create([
            'body' => 'This is a comment on a category.',
        ]);
    }
}
