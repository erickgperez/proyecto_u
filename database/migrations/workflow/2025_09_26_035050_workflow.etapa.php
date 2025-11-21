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
            $table->uuid('uuid')->unique()->after('id');

            $table->comment('Las etapas por las que pasa un flujo');

            $table->string('codigo', length: 100);
            $table->string('nombre', length: 255);
            $table->text('indicaciones')->nullable();

            $table->foreignId('flujo_id')->comment('Flujo o proceso al que pertenece la etapa');
            $table->foreign('flujo_id')->references('id')->on('workflow.flujo')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('lugar_id')->nullable();
            $table->foreign('lugar_id')->references('id')->on('workflow.lugar')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->unsignedBigInteger('created_by')->nullable()->comment('Usuario que creó el registro');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Usuario que realizó la última actualización del registro');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

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
