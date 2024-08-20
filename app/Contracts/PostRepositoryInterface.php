<?php

namespace App\Contracts;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getAllPosts();
    
    public function getPostById($id);
    
    public function createPost(array $data);
    
    public function updatePost(Post $post, array $data);
    
    public function deletePost(Post $post);
    
    // Additional methods for business logic
    public function performBusinessLogic(Post $post);
}
