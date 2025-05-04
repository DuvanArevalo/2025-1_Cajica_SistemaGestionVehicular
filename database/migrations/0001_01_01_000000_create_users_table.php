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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Datos del usuario
            $table->string('name1');
            $table->string('name2')->nullable();
            $table->string('lastname1');
            $table->string('lastname2')->nullable();
            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->foreignId('role_id')                // tabla roles
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->default(3);

            $table->foreignId('document_type_id')       // id_tipo_documento
                ->constrained('document_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('document_number', 20);      // número de documento

            // Estado y auditoría
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletesTz();

            // Restricción compuesta: tipo de doc + número
            $table->unique(['document_type_id', 'document_number'], 'unique_document_per_type');
            $table->unique('email', 'unique_email');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
