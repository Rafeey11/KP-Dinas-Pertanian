<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_request_id')->constrained('report_requests')->cascadeOnDelete();
            $table->foreignId('penyuluh_id')->constrained('penyuluhs')->cascadeOnDelete();
            $table->foreignId('bpp_id')->constrained('bpps')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('status', 30)->default('pending'); // pending, approved, revision, rejected
            $table->text('admin_notes')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->unique(['report_request_id', 'penyuluh_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_submissions');
    }
};
