<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\CategoryRepositoryInterface;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function detail() //one-to-one
    {
        return $this->hasOne(CategoryDetail::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    // Define a repository interface to follow DIP
    public function setCategoryRepository(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    // Implement any additional methods or logic through the repository
    public function someBusinessLogic()
    {
        return $this->categoryRepository->performBusinessLogic($this);
    }
}
