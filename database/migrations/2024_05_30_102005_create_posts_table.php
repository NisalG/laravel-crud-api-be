<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('author_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('content');
            $table->fullText('content');
            $table->string('featured_image')->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_size')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('share_image')->nullable();
            $table->json('share_image_name')->nullable();
            $table->string('share_image_size')->nullable();
            $table->string('share_image_alt')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedBigInteger('category_id')->default(1); // 1 = uncategorized - if CSV doesn't have a category, default to uncategorized
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->boolean('published')->default(false);
            $table->dateTime('published_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
