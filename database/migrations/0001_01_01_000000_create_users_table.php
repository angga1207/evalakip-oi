<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('instances', function (Blueprint $table) {
            $table->id();
            $table->integer('id_eoffice')->nullable();
            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('code')->nullable();
            $table->text('logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->double('skor')->default(0);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->text('image');
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('instance_id')
                ->nullable()
                ->constrained('instances')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('jabatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('instances');
        Schema::dropIfExists('units');
    }
};
