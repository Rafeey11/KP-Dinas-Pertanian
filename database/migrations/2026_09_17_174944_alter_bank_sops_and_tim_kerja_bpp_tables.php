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
        Schema::table('bank_sops', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->unsignedBigInteger('creator_id')->nullable()->change();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('admin_tim_kerja_bpp', function (Blueprint $table) {
            $table->unique(['user_id', 'bpp_id'], 'user_bpp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_tim_kerja_bpp', function (Blueprint $table) {
            $table->dropUnique('user_bpp_unique');
        });

        Schema::table('bank_sops', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->unsignedBigInteger('creator_id')->nullable(false)->change();
            $table->foreign('creator_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
