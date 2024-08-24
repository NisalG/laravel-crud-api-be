<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\PostRepositoryInterface;
use App\Traits\Likeable;

class Post extends Model
{
    use HasFactory, Likeable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
        'likes_count', // for the Likeable trait
        'dislikes_count', // for the Likeable trait 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    //  Custom Scope
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at'); // Usage: $publishedPosts = Post::published()->get();
    }

    // Mutator
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }

    // Accessor
    public function getTitleAttribute($value)
    {
        return strtoupper($value);
    }

    public function user() // belongsTo relationship - for hasManyThrough
    {
        return $this->belongsTo(User::class);
    }

    // Define a repository interface to follow DIP
    public function setPostRepository(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    // Implement any additional methods or logic through the repository
    public function someBusinessLogic()
    {
        return $this->postRepository->performBusinessLogic($this);
    }
}
