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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();            
            $table->longText('description')->nullable();            
            $table->string('language')->default('English');
            $table->integer('duration')->nullable();            
            $table->decimal('price', 8, 2)->default(0.00);            
            $table->integer('max_students')->nullable();            
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner');
            $table->boolean('is_highlight')->default(false);
            $table->string('thumbnail_image')->nullable();
            $table->string('promo_video')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
