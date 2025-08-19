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
        Schema::create('ref_periode', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('label');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('components', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('ref_periode_id')
                ->constrained('ref_periode', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('nama');
            $table->double('bobot');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('ref_jawaban', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('label');
            $table->timestamps();
        });

        Schema::create('ref_jawaban_value', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('ref_jawaban_id')
                ->constrained('ref_jawaban', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('label');
            $table->double('nilai');
            $table->timestamps();
        });

        Schema::create('criterias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('component_id')
                ->constrained('components', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('ref_periode_id')
                ->constrained('ref_periode', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('nama');
            $table->text('penjelasan')->nullable();
            $table->foreignId('ref_jawaban_id')
                ->constrained('ref_jawaban', 'id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->double('bobot');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias');
        Schema::dropIfExists('ref_jawaban_value');
        Schema::dropIfExists('ref_jawaban');
        Schema::dropIfExists('components');
        Schema::dropIfExists('ref_periode');
    }
};
