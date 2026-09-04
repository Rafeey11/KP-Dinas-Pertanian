<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_submission_id')->constrained('report_submissions')->cascadeOnDelete();
            $table->integer('version_number')->default(1);
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->integer('file_size'); // bytes
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_versions');
    }
};
