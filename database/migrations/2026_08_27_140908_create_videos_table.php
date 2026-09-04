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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bpp_id')->nullable()->constrained('bpps')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('source_type', 30);
            $table->text('video_url');
            $table->date('activity_date')->nullable();
            $table->string('status', 20)->default('inactive');
            $table->boolean('show_on_homepage')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
