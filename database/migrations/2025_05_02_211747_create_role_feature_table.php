<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_feature', function (Blueprint $table) {
            $table->id();                   // id_rol_funcionalidad → id
            $table->foreignId('role_id')    // id_rol
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('feature_id') // id_funcionalidad
                ->constrained('features')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();           // creado_en y actualizado_en
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_feature');
    }
};
