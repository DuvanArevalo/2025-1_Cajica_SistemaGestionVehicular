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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id(); // id_tipo_documento → id (convención Laravel)
            $table->string('name'); // tipo_documento
            $table->string('abbreviation',10); // abreviatura
            $table->timestamps(); // creado_en y actualizado_en (estándar de Laravel)

            $table->unique('name', 'unique_document_type_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_types');
    }
};
