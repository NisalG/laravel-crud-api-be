<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'published_at',
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
}
