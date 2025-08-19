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
        Schema::create('jawaban', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('ref_periode_id')
                ->constrained('ref_periode', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('criteria_id')
                ->constrained('criterias', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('ref_jawaban_id')
                ->constrained('ref_jawaban', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('instance_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('evaluator_id')
                ->nullable()
                ->constrained('users', 'id');
            $table->double('skor')->default(0);
            $table->text('catatan')->nullable();
            $table->text('evidence')->nullable();
            $table->text('catatan_evaluator')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};
