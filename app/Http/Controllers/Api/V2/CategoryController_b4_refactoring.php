<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

use App\Models\Category;
use App\Exceptions\AuthorizationException;
use App\Exceptions\DatabaseException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ValidationException;
use App\Mail\CategoryDeleteEmail;
use Illuminate\Support\Facades\Mail;

class CategoryController extends Controller
{
    public function index()
    {
        $cachedCategories = Redis::get('categories.all');

        if ($cachedCategories) {
            $categories = json_decode($cachedCategories, true);
        } else {
            $categories = Category::all();
            Redis::set('categories.all', json_encode($categories));
        }

        return response()->json($categories);
    }

    public function show($id)
    {
        $cachedCategory = Redis::get("category.$id");

        if ($cachedCategory) {
            $category = json_decode($cachedCategory, true);
        } else {
            $category = Category::find($id);
            if (!$category) {
                throw new EntityNotFoundException('Category');
            }
            Redis::set("category.$id", json_encode($category));
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
            // Clear cache for all categories
            Redis::del('categories.all');
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to create category");
        }

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }

        $category = Category::find($id);
        if (!$category) {
            throw new EntityNotFoundException('Category');
        }

        try {
            $category->update($request->all());
            // Clear cache for the updated category and all categories
            Redis::del("category.$id");
            Redis::del('categories.all');
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to update category");
        }

        return response()->json($category, 200);
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

            // Clear cache for the deleted category and all categories
            // Redis::del("category.$id");
            // Redis::del('categories.all');

            // Send email
            $emailData = [
                'title' => 'This is Test Mail Title',
                'body' => 'This email body is for testing purposes',
            ];

            // Do not send attachments with $attachments and assign to a $attachments variable to the below class and they will be read automtically by Laravel
            // If you want to send attachments from here, add them to the $emailData variable
            Mail::to('tempmail@gmail.com')->send(new CategoryDeleteEmail($emailData));
        } catch (DatabaseException $e) {
            // Handle DatabaseException specifically
            throw new DatabaseException("Failed to delete category: " . $e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            // Handle any other exceptions
            throw new \Exception("Failed to delete category: " . $e->getMessage(), $e->getCode(), $e);
        }

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

    // Eager load categories with their posts in one query
    // The query is executed only once, when the first category is fetched.
    // Solves the N+1 query problem by loading all related models in one query. This is generally more efficient when you need related models for multiple parent models.

    public function getCategoriesWithPostsEager()
    {
        // Fetch all categories with their posts eagerly loaded
        $cachedCategories = Redis::get('categories.with.posts');

        if ($cachedCategories) {
            $categories = json_decode($cachedCategories, true);
        } else {
            $categories = Category::with('posts')->get();
            Redis::set('categories.with.posts', json_encode($categories));
        }

        return response()->json($categories);
    }

    // Lazy load categories then their posts
    // Suppose you want to fetch all categories and later access their related posts.
    // All categories are fetched with $categories = Category::all();.
    // When $category->posts is accessed within the loop, Laravel will lazy load the related posts for each category.
    // Can lead to the N+1 query problem, where N additional queries are executed for each related model. In the above lazy loading example, if you have 10 categories, you will end up with 11 queries (1 query for categories and 1 for each category's posts).

    public function getCategoriesWithPostsLazy()
    {
        // Fetch all categories
        $cachedCategories = Redis::get('categories.all');

        if ($cachedCategories) {
            $categories = json_decode($cachedCategories, true);
        } else {
            $categories = Category::all();
            Redis::set('categories.all', json_encode($categories));
        }

        return response()->json($categories);

        // Lazy load posts for each category in FE / Blade
        // foreach ($categories as $category) {
        //     // Access posts, which triggers lazy loading
        //     $posts = $category->posts;

        //     // Do something with the posts
        //     foreach ($posts as $post) {
        //         echo $post->title . '<br>';
        //     }
        // }
    }

    // Subqueries: Get categories with the count of posts.
    public function getCategoriesWithPostsCount()
    {
        $cachedCategoriesCount = Redis::get('categories.with.posts.count');

        if ($cachedCategoriesCount) {
            $categories = json_decode($cachedCategoriesCount, true);
        } else {
            $categories = Category::withCount('posts')->get();
            Redis::set('categories.with.posts.count', json_encode($categories));
        }

        return response()->json($categories);
    }

    // Polymorphic Relationship usage sample - Adding a comment to a category.
    // Also see PostController >> addPostComment - Adding a comment to a post.
    public function addCategoryComment(Request $request)
    {
        $category = Category::find($request->category_id);
        if (!$category) {
            throw new EntityNotFoundException('Category');
        }

        $category->comments()->create([
            'body' => 'This is a comment on a category.',
        ]);
        
        // Optionally clear cache related to the category and all categories
        Redis::del("category.{$category->id}");
        Redis::del('categories.all');

        return response()->json(['message' => 'Comment added successfully'], 201);
    }
}
