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
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();                   // id_tipo_vehiculo → id
            $table->string('name', 150);    // nombre_tipo
            $table->string('description');  // descripcion_tipo
            $table->timestamps();           // creado_en y actualizado_en

            $table->unique('name', 'unique_vehicle_type_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_types');
    }
};
