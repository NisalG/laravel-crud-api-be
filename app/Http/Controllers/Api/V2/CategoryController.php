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
use App\Services\CategoryValidatorService;
use App\Contracts\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {

        //long: using redis::get/set
        // $cachedCategories = Redis::get('categories.all');

        // if ($cachedCategories) {
        //     $categories = json_decode($cachedCategories, true);
        // } else {
        //     $categories = Category::all();
        //     Redis::set('categories.all', json_encode($categories));
        // }

        // short using redis::remember (expires in 60 seconds)
        try {
            // Fetch categories from Redis or retrieve from repository if not cached
            $categories = Redis::remember('categories.all', 60 * 60, function () {
                return $this->categoryRepository->getAllCategories();
            });

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to retrieve categories");
        }
    }

    public function show($id)
    {
        //long: using redis::get/set
        // $cachedCategory = Redis::get("category.$id");

        // if ($cachedCategory) {
        //     $category = json_decode($cachedCategory, true);
        // } else {
        //     $category = Category::find($id);
        //     if (!$category) {
        //         throw new EntityNotFoundException('Category');
        //     }
        //     Redis::set("category.$id", json_encode($category));
        // }

        // short using redis::remember (expires in 60 seconds)
        try {
            // Fetch the category from Redis or retrieve from repository if not cached
            $category = Redis::remember("category.$id", 60 * 60, function () use ($id) {
                $category = $this->categoryRepository->findCategoryById($id);

                if (!$category) {
                    throw new EntityNotFoundException('Category');
                }

                return $category;
            });

            return response()->json($category, 200);
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to retrieve category");
        }
    }

    public function store(Request $request)
    {
        $validator = CategoryValidatorService::validateCreateRequest($request);

        if ($validator->fails()) {
            // Throw a custom validation exception if validation fails
            // throw new ValidationException($validator->errors()->all());

            // OR
            return response()->json($validator->errors(), 422);
        }

        try {
            // Create the category using the repository
            $category = $this->categoryRepository->createCategory($request->all());

            // Clear cache for all categories
            Redis::del('categories.all');
        } catch (\Exception $e) {
            // Throw a custom database exception if an error occurs during creation
            throw new DatabaseException("Failed to create category");
        }

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = CategoryValidatorService::validateCreateRequest($request);

        if ($validator->fails()) {
            // Throw a custom validation exception if validation fails
            // throw new ValidationException($validator->errors()->all());

            // OR
            return response()->json($validator->errors(), 422);
        }

        $category = $this->categoryRepository->findCategoryById($id);
        if (!$category) {
            // Throw a custom exception if the category is not found
            throw new EntityNotFoundException('Category');
        }

        try {
            // Update the category using the repository
            $category->updateCategory($validator->validated());

            // Clear cache for the updated category and all categories
            Redis::del("category.$id");
            Redis::del('categories.all');
        } catch (\Exception $e) {
            // Throw a custom database exception if an error occurs during the update
            throw new DatabaseException("Failed to update category");
        }

        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        try {

            $category = $this->categoryRepository->findCategoryById($id);
            
            if (!$category) {
                throw new EntityNotFoundException('Category');
            }

            // Check if the user is authorized to delete the category
            // This will work only after Authorization is developed
            // if (Auth::user()->cannot('delete', $category)) {
            //     throw new AuthorizationException("You are not authorized to delete this category");
            // }
            
            // Delete the category using the repository
            $this->categoryRepository->deleteCategory($id);

            // Clear cache for the deleted category and all categories
            Redis::del("category.$id");
            Redis::del('categories.all');

            // Send email
            $emailData = [
                'title' => 'This is Test Mail Title',
                'body' => 'This email body is for testing purposes',
            ];

            // Do not send attachments with $attachments and assign to a $attachments variable to the below class and they will be read automtically by Laravel
            // If you want to send attachments from here, add them to the $emailData variable
            Mail::to('tempmail@gmail.com')->send(new CategoryDeleteEmail($emailData));
                
            // return response()->json(['message' => 'Category deleted successfully'], 200);
            // OR
            return response()->json(null, 204);
        } catch (DatabaseException $e) {
            // Handle DatabaseException specifically
            throw new DatabaseException("Failed to delete category: " . $e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            // Handle any other exceptions
            throw new \Exception("Failed to delete category: " . $e->getMessage(), $e->getCode(), $e);
        }
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
