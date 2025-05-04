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
        Schema::create('answers', function (Blueprint $table) {
            $table->id(); // id_respuesta → id

            $table->foreignId('form_id') // id_formulario
                ->constrained('preoperational_forms')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('question_id') // id_pregunta
                ->constrained('questions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->boolean('value')->nullable(); // respuesta (0 o 1, NULLable)

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
        Schema::dropIfExists('answers');
    }
};
