<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workflow.etapa', function (Blueprint $table) {
            $table->id();

            $table->comment('Las etapas por las que pasa un flujo');

            $table->string('nombre', length: 255);
            $table->text('indicaciones');

            $table->foreignId('flujo_id');
            $table->foreign('flujo_id')->references('id')->on('workflow.flujo')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('lugar_id');
            $table->foreign('lugar_id')->references('id')->on('workflow.lugar')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow.etapa');
    }
};
