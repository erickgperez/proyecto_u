<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Documento\TipoSeeder;
use Database\Seeders\PlanEstudio\Carrera2Seeder;
use Database\Seeders\PlanEstudio\CarreraSeeder;
use Database\Seeders\PlanEstudio\GradoSeeder;
use Database\Seeders\PlanEstudio\TipoCarreraSeeder;
use Database\Seeders\Secundaria\SectorSeeder;
use Database\Seeders\Workflow\EstadoSeeder;
use Database\Seeders\Workflow\EtapaSeeder;
use Database\Seeders\Workflow\FlujoSeeder;
use Database\Seeders\Workflow\TipoCarreraSedeSolicitudSeeder;
use Database\Seeders\Workflow\TipoFlujoSeeder;
use Database\Seeders\Workflow\TransicionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            RolesAndPermissionsSeeder::class,
            DepartamentoSeeder::class,
            TipoSeeder::class,
            MunicipioSeeder::class,
            DistritoSeeder::class,
            SectorSeeder::class,
            GradoSeeder::class,
            TipoCarreraSeeder::class,
            CarreraSeeder::class,
            Carrera2Seeder::class,
            SexoSeeder::class,
            PaisSeeder::class,
            TipoFlujoSeeder::class,
            TipoCarreraSedeSolicitudSeeder::class,
            EstadoSeeder::class,
            FlujoSeeder::class,
            EtapaSeeder::class,
            TransicionSeeder::class
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ])->each(function ($user) {
            $user->assignRole('super-admin');
        });
    }
}
