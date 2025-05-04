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
        Schema::create('observations', function (Blueprint $table) {
            $table->id(); // id_observacion → id

            $table->foreignId('form_id') // id_formulario
                ->constrained('preoperational_forms')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('section_id') // id_seccion
                ->constrained('sections')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->text('text')->nullable(); // observacion

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
        Schema::dropIfExists('observations');
    }
};
