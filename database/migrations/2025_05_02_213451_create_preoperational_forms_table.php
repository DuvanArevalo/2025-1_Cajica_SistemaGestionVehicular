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
        Schema::create('preoperational_forms', function (Blueprint $table) {
            $table->id();                   // id_formulario_preoperacional → id

            $table->foreignId('vehicle_id') // id_vehiculo
                ->constrained('vehicles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('user_id')    // id_usuario
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('new_mileage'); // nuevo_kilometraje

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
        Schema::dropIfExists('preoperational_forms');
    }
};
