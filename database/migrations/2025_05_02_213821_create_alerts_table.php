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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id(); // id_alerta → id

            $table->foreignId('form_id') // id_formulario
                ->constrained('preoperational_forms')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('answer_id') // id_respuesta
                ->nullable()
                ->constrained('answers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('observation_id') // id_observacion
                ->nullable()
                ->constrained('observations')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('alert_status_id') // id_estado_alerta
                ->constrained('alert_statuses')
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
        Schema::dropIfExists('alerts');
    }
};
