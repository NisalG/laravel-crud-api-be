<?php
namespace App\Services;

use Illuminate\Support\Facades\Validator;

class PostValidatorService
{
    public static function validateCreateRequest($request)
    {
        return Validator::make($request->all(), [
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'category_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'required',
            'published_at' => 'required|date'
        ]);
    }

    public static function validateUpdateRequest($request, $postId)
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug,' . $postId,
            'meta_title' => 'nullable|string',
            'meta_des' => 'nullable|string',
            'content' => 'required|string',
            'featured_image_name' => 'nullable|string',
            'image_size' => 'nullable|string',
            'image_alt' => 'nullable|string',
            'share_image' => 'nullable|json',
            'share_image_video_url' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'published_at' => 'nullable|date_format:Y-m-d H:i:s',
            'published' => 'boolean',
        ]);
    }
}
