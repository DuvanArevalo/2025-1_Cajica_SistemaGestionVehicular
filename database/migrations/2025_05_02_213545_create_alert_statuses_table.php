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
        Schema::create('alert_statuses', function (Blueprint $table) {
            $table->id();                   // id_estado_alerta → id
            $table->string('type', 50);     // tipo_estado
            $table->string('description');  // descripcion
            $table->timestamps();           // creado_en y actualizado_en

            $table->unique('type', 'unique_alert_status_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_statuses');
    }
};
