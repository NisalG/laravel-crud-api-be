<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'description',
    ];

    public function category() //one-to-one
    {
        return $this->belongsTo(Category::class);
    }
}
