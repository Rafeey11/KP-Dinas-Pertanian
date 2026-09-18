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
        Schema::create('bank_sops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->enum('type', ['document', 'link', 'template', 'internal_form']);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('url')->nullable();
            $table->enum('target_type', ['all_bpp', 'selected_bpp']);
            $table->dateTime('deadline')->nullable();
            $table->enum('status', ['draft', 'published', 'inactive', 'archived'])->default('draft');
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('bank_sop_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_sop_id')->constrained('bank_sops')->cascadeOnDelete();
            $table->foreignId('bpp_id')->constrained('bpps')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('bank_sop_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_sop_id')->constrained('bank_sops')->cascadeOnDelete();
            $table->string('field_name');
            $table->string('label');
            $table->string('type'); // text, textarea, number, date, etc
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('bank_sop_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_sop_id')->constrained('bank_sops')->cascadeOnDelete();
            $table->foreignId('bpp_id')->constrained('bpps')->cascadeOnDelete();
            $table->foreignId('submitter_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted'])->default('submitted');
            $table->timestamps();
        });

        Schema::create('bank_sop_submission_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('bank_sop_submissions')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('bank_sop_fields')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_sop_submission_values');
        Schema::dropIfExists('bank_sop_submissions');
        Schema::dropIfExists('bank_sop_fields');
        Schema::dropIfExists('bank_sop_targets');
        Schema::dropIfExists('bank_sops');
    }
};
