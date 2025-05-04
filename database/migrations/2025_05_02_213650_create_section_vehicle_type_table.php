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
        Schema::create('section_vehicle_type', function (Blueprint $table) {
            $table->id(); // id_seccion_tipo_vehiculo → id

            $table->foreignId('section_id') // id_seccion
                ->constrained('sections')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('vehicle_type_id') // id_tipo_vehiculo
                ->constrained('vehicle_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps(); // creado_en y actualizado_en
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_vehicle_type');
    }
};
