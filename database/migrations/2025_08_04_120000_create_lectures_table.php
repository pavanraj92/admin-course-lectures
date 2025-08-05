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
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('course_sections')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->enum('type', ['video', 'audio', 'text', 'quiz'])->default('video');
            $table->string('video')->nullable(); // Path to video file
            $table->string('attachment')->nullable(); // Path to attachment file
            $table->integer('duration')->nullable(); // Duration in minutes
            $table->integer('order')->default(0); // Order within section
            $table->boolean('is_preview')->default(false); // Can be previewed without enrollment
            $table->boolean('is_highlight')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index(['section_id', 'order']);
            $table->index(['section_id', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
