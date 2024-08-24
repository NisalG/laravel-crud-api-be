<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Likeable;

class Comment extends Model
{
    use HasFactory, Likeable;

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'body',
        'likes_count', // for the Likeable trait
        'dislikes_count', // for the Likeable trait 
    ];

    public function commentable()
    {
        return $this->morphTo();
    }
}
