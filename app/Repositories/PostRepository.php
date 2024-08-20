<?php

namespace App\Repositories;

use App\Contracts\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getAllPosts()
    {
        return $this->post->all();
    }

    public function getPostById($id)
    {
        return $this->post->findOrFail($id);
    }

    public function createPost(array $data)
    {
        return $this->post->create($data);
    }

    public function updatePost(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }

    public function deletePost(Post $post)
    {
        return $post->delete();
    }

    // Example method for additional business logic
    public function performBusinessLogic(Post $post)
    {
        // Implement some business logic here
        return $post->someProperty; // Placeholder
    }
}
