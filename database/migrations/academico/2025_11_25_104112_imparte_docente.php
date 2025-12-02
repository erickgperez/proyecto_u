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
        Schema::create('academico.imparte_docente', function (Blueprint $table) {
            $table->id();
            $table->comment('Para definir las carga académica de los docentes');

            $table->foreignId('imparte_id')->comment('la sede donde se imparte la unidad académica');
            $table->foreign('imparte_id')->references('id')->on('academico.imparte')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('docente_id')->comment('Id del docente');
            $table->foreign('docente_id')->references('id')->on('academico.docente')->onDelete('RESTRICT')->onUpdate('CASCADE');

            //$table->foreignId('forma_imparte_id')->comment('Id de la forma de impartir');
            //$table->foreign('forma_imparte_id')->references('id')->on('academico.forma_imparte')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.imparte_docente');
    }
};
