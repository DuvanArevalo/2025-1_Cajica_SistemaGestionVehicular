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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();                           // id_vehiculo → id

            $table->foreignId('vehicle_type_id')    // id_tipo_vehiculo
                ->constrained('vehicle_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('brand_id')           // id_marca
                ->constrained('brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('model_id')                           // id_modelo
                ->constrained('vehicles_models')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('model_year', 4);                        // anio_modelo
            $table->string('wheel_count', 2);                       // numero_ruedas
            $table->string('color', 50);
            $table->string('plate', 6)->unique();                   // placa

            $table->unsignedInteger('mileage')->default(0);         // kilometraje

            $table->boolean('is_active')->default(true);            // estado_vehiculo
            $table->timestamp('soat')->nullable();                  // soat
            $table->boolean('soat_status')->default(true);          // estado_soat
            $table->timestamp('mechanical_review')->nullable();     // tecnomecanica
            $table->boolean('mechanical_review_status')->default(true); // estado_tecnomecanica

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
        Schema::dropIfExists('vehicles');
    }
};
