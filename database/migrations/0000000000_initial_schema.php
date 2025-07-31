<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        DB::statement('DROP SCHEMA IF EXISTS registro_acad CASCADE');
        DB::statement('CREATE SCHEMA registro_acad');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
