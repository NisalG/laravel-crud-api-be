<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
* @OA\Schema(
* schema="Post",
* type="object",
* title="Post",
* required={"title", "content"},
* properties={
* @OA\Property(property="id", type="integer", format="int64"),
* @OA\Property(property="title", type="string"),
* @OA\Property(property="slug", type="string"),
* @OA\Property(property="content", type="string"),
* @OA\Property(property="category_id", type="number"),
* @OA\Property(property="published_at", type="date"),
* }
* )

* @OA\Schema(
* schema="CreatePost",
* type="object",
* title="Create Post",
* required={"title", "slug", "content", "category_id","published_at"},
* @OA\Property(property="title", type="string"),
* @OA\Property(property="slug", type="string"),
* @OA\Property(property="content", type="string"),
* @OA\Property(property="category_id", type="integer"),
* @OA\Property(property="published_at", type="string", format="date-time", nullable=true)
* )

* @OA\Schema(
* schema="UpdatePost",
* type="object",
* title="Update Post",
* required={"id", "title", "slug", "content", "category_id"},
* @OA\Property(property="id", type="integer", format="int64"),
* @OA\Property(property="title", type="string"),
* @OA\Property(property="slug", type="string"),
* @OA\Property(property="content", type="string"),
* @OA\Property(property="category_id", type="integer"),
* @OA\Property(property="published_at", type="string", format="date-time", nullable=true)
* )
*/

class PostSchema {}