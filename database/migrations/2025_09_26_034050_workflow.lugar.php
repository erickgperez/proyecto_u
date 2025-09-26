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
        Schema::create('workflow.lugar', function (Blueprint $table) {
            $table->id();

            $table->comment('Identifica los lugares físicos donde se encuentra una etapa');

            $table->string('codigo', length: 100)->unique();
            $table->string('descripcion', length: 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow.lugar');
    }
};
