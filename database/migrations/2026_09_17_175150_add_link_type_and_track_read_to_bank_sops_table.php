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
            if (!Schema::hasColumn('bank_sops', 'link_type')) {
                $table->string('link_type', 50)->nullable()->after('type');
            }
            if (!Schema::hasColumn('bank_sops', 'track_read')) {
                $table->boolean('track_read')->default(false)->after('deadline');
            }
        });

        Schema::create('bank_sop_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_sop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bpp_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_sop_reads');
        Schema::table('bank_sops', function (Blueprint $table) {
            $table->dropColumn(['link_type', 'track_read']);
        });
    }
};
