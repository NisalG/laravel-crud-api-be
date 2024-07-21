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
        Schema::create('category_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->text('description');
            $table->timestamps();

            // Foreign Key Constraint -short form:
            // $table->foreignId('user_id')
                // ->constrained()
                // ->cascadeOnDelete(); // creates a foreign key in Laravel migrations. It automatically sets up the column, references the related table's ID (usually id), and creates an index for efficiency. This concise approach improves readability and simplifies foreign key definition.
            
            // Foreign Key Constraint -descriptive form:
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade'); // Optional: Delete detail if category is deleted
                // ->onUpdate('cascade') // Optional: Not a default in Laravel. Ensures that when a category's primary key (id) is updated, the corresponding entry in category_details will also have its category_id updated automatically. 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /* Laravel's dropForeign removes foreign key constraints from tables in migrations. It helps during schema changes or refactoring relationships. You can target constraints by column names or their specific names. Use it carefully to avoid compromising data integrity and handle potential orphaned data.  */
        // $table->dropForeign('category_id');
        Schema::dropIfExists('category_details');
    }
};
