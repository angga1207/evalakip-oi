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
        Schema::create('instance_skor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instance_id')->constrained('instances')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('ref_periode')->onDelete('cascade');
            $table->decimal('skor', 8, 2)->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['instance_id', 'periode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instance_skor');
    }
};
