<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_type_id')->constrained('report_types')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('period', 50); // e.g. "Agustus 2026"
            $table->integer('period_year');
            $table->date('start_date')->nullable();
            $table->date('deadline');
            $table->json('allowed_formats')->nullable(); // ["pdf","docx"]
            $table->integer('max_file_size')->default(10240); // KB
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active'); // active, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_requests');
    }
};
