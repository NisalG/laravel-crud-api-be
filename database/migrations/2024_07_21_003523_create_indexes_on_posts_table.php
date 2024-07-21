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
        Schema::table('posts', function (Blueprint $table) {
            // Ensure indexes are only added if they don't already exist
            if (!Schema::hasColumn('posts', 'title') || !Schema::hasColumn('posts', 'published')) {
                $table->index(['title', 'published'], 'posts_title_published_index');  // Index on 'title' and 'published' columns
            }

            if (!Schema::hasColumn('posts', 'category_id')) {
                $table->index('category_id', 'category_index'); // Index on 'category_id' with custom name
            }
            
            // Unique index on slug added when creating the posts table
            // $table->unique('slug');

            /*
                Use fullText() to mark a column as searchable within Laravel's Eloquent queries.
                Use fullTextIndex() to create a dedicated full-text index for optimal search performance on large datasets.
            */
            // Full Text index on content (already exists in your original migration)
            // $table->fullTextIndex('content'); // You can uncomment this if it was removed

            // Consider adding additional indexes based on your queries
            // For example, if you frequently filter by video_url:
            // $table->index('video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_title_published_index'); // Drop the composite index
            $table->dropIndex('category_index'); // Drop the custom named index
            // Assuming unique index on 'slug' is already added when creating the table
            // $table->dropUnique('posts_slug_unique'); // Drop the unique index on 'slug'

            // Drop full text index if it was created
            // $table->dropFullText(['content']); // Uncomment if full text index was created

            // Drop additional indexes if they were added
            // For example, if you added an index on 'video_url':
            // $table->dropIndex(['video_url']);
        });
    }
};
