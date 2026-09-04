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
        Schema::create('penyuluhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bpp_id')->constrained('bpps')->cascadeOnDelete();
            $table->string('nip', 50)->nullable()->unique();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('photo', 255)->nullable();
            $table->string('position', 150)->nullable();
            $table->string('employment_status', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('working_area', 255)->nullable();
            $table->string('village_area', 255)->nullable();
            $table->text('farmer_group')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('status', 20)->default('active');
            $table->timestamps();
            
            $table->index('name');
            $table->index('status');
            $table->index('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyuluhs');
    }
};
