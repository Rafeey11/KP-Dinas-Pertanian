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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bpp_id')->nullable()->constrained('bpps')->nullOnDelete();
            $table->foreignId('author_penyuluh_id')->nullable()->constrained('penyuluhs')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('news_categories')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail', 255)->nullable();
            $table->string('activity_location', 255)->nullable();
            $table->date('activity_date')->nullable();
            $table->string('status', 30)->default('draft');
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
