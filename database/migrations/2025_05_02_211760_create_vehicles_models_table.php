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
        Schema::create('vehicles_models', function (Blueprint $table) {
            $table->id();                   // id_modelo → id
            $table->string('name', 150);    // nombre_modelo

            $table->foreignId('brand_id')   // id_marca
                ->constrained('brands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();           // creado_en y actualizado_en

            // nombre_modelo debe ser único por marca
            $table->unique(['name', 'brand_id'], 'unique_model_per_brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles_models');
    }
};
