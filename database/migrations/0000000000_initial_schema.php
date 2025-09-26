<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS secundaria CASCADE');
        DB::statement('CREATE SCHEMA secundaria');

        DB::statement('DROP SCHEMA IF EXISTS ingreso CASCADE');
        DB::statement('CREATE SCHEMA ingreso');

        DB::statement('DROP SCHEMA IF EXISTS academico CASCADE');
        DB::statement('CREATE SCHEMA academico');

        DB::statement('DROP SCHEMA IF EXISTS plan_estudio CASCADE');
        DB::statement('CREATE SCHEMA plan_estudio');

        DB::statement('DROP SCHEMA IF EXISTS documento CASCADE');
        DB::statement('CREATE SCHEMA documento');

        DB::statement('DROP SCHEMA IF EXISTS workflow CASCADE');
        DB::statement('CREATE SCHEMA workflow');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
