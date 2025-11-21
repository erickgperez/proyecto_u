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
        Schema::create('documento.persona_documento', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Relación de muchos a muchos entre persona y documento');

            $table->foreignId('persona_id')->comment('Id de la persona a quien pertenece el documento');
            $table->foreign('persona_id')->references('id')->on('public.persona')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('documento_id')->comment('Id del documento');
            $table->foreign('documento_id')->references('id')->on('documento.documento')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento.persona_documento');
    }
};
