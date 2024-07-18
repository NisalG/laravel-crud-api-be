<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use App\Swagger\Schemas;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     operationId="getPosts",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     summary="Get list of posts",
     *     description="Returns a list of posts",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        // Without using API resource
        // $posts = Post::all();
        // return response()->json($posts);

        // With using API resource
        try {
            $posts = Post::all();

            // Check if posts are retrieved successfully
            if ($posts->isEmpty()) {
                return response()->json(['message' => 'No posts found'], 404);
            }

            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve posts', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Store a newly created post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreatePost")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="errors", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|min:5|max:255|unique:posts,slug', // Unique slug validation
            'category_id' => 'required|integer',
            'content' => 'required',
            'published_at' => 'required|date'
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check for validation errors
        if ($validator->fails()) {
            // Log the validation errors for debugging
            Log::debug('Validation failed', $validator->errors()->toArray());
            return response()->json($validator->errors(), 422); // Unprocessable Entity
        }

        try {
            // Create a new Post instance
            $post = new Post;

            // Assign data to the Post model
            $post->title = $request->input('title');
            $post->slug = $request->input('slug');
            $post->category_id = $request->input('category_id');
            $post->content = $request->input('content');
            $post->published_at = $request->input('published_at');

            // Save the Post to the database
            $post->save();

            // Without using API resource
            // Return a successful response with the created Post data
            // return response()->json($post, 201); // Created

            // With using API resource
            // $post = Post::create($post);
            // return new PostResource($post);
            return response()->json(new PostResource($post), 201); // Created
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create post', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     operationId="getPost",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     summary="Get a single post",
     *     description="Returns a single post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Without using API resource
        // $post = Post::find($id);
        // if (!$post) {
        //     return response()->json(['message' => 'Post not found'], 404);
        // }
        // return response()->json($post);

        // With using API resource
        try {
            $post = Post::findOrFail($id);
            return new PostResource($post);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve post', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     operationId="updatePost",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     summary="Update a post",
     *     description="Updates an existing post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="errors", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        dd($request);
        try {
            $post = Post::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|unique:posts,slug,' . $post->id, // Update validation for unique slug excluding current post
                'meta_title' => 'nullable|string',
                'meta_des' => 'nullable|string',
                'content' => 'required|string',
                'featured_image_name' => 'nullable|string',
                'image_size' => 'nullable|string',
                'image_alt' => 'nullable|string',
                'share_image' => 'nullable|json',
                'share_image_video_url' => 'nullable|string',
                'category_id' => 'nullable|integer',
                'published_at' => 'nullable|date_format:Y-m-d H:i:s', // Added published_at validation
                'published' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Use validated fields only
            $validatedData = $validator->validated();

            // Update the post
            $post->update($validatedData);

            return new PostResource($post);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update post', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     operationId="deletePost",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     summary="Delete a post",
     *     description="Deletes a post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Post deleted successfully")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Post not found")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Unauthorized")
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        // Without using API resource
        // $post = Post::find($id);
        // if (!$post) {
        //     return response()->json(['message' => 'Post not found'], 404);
        // }
        // $post->delete();
        // return response()->json(['message' => 'Post deleted successfully']);

        // With using API resource
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete post', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search FAQ Answers by matching text.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/posts/answers",
     *     operationId="getAnswers",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     summary="Get FAQ Answers By Question Text",
     *     description="Get answers to frequently asked questions (FAQs) based on a provided question text.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="faq",
     *         in="query",
     *         description="The question text to search for answers.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             minLength=3,
     *             maxLength=255
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="content", type="string", description="The content of the FAQ answer"),
     *                         @OA\Property(property="relevance", type="number", description="Relevance score of the answer to the question text"),
     *                     }
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", description="Validation error message"),
     *                 @OA\Property(property="errors", type="object", description="Specific validation errors"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", description="Error message indicating missing or invalid authentication token"),
     *             )
     *         )
     *     ),
     * )
     */
    public function getAnswers(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->query(), [
            'faq' => 'required|string|min:3|max:255'
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // Unprocessable Entity
        }

        // Get the validated 'faq' parameter
        $faq = $request->query('faq');

        // $results = DB::select(
        //     "SELECT content, MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance
        //      FROM posts
        //      WHERE MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)
        //      ORDER BY relevance DESC",
        //     [$faq, $faq]
        // );

        $results = DB::table('posts')
            ->select('content', DB::raw('MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance'))
            ->whereRaw('MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$faq])
            ->addBinding([$faq], 'select')
            ->orderByDesc('relevance')
            ->get();



        return response()->json($results);
    }

    public function getPublishedPosts(Request $request)
    {
        $publishedPosts = Post::published()->get();
        return response()->json($publishedPosts);
    }
}
