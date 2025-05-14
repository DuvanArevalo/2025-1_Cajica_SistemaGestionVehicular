<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('question_section', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
            ->constrained()
            ->onDelete('cascade');

            $table->foreignId('section_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->timestamps();
            
            $table->unique(['question_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('question_section');
    }
};
