<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional)
        DB::table('roles')->delete();

        $now = Carbon::now();

        $roles = [
            ['name' => 'admin', 'description' => 'Administrador del sistema, tiene control total', 'created_at' => $now, 'updated_at' => $now], // Asume una columna 'description' opcional
            ['name' => 'sst', 'description' => 'Seguridad y Salud en el Trabajo, responsable de la gestion de vehiculos, personal y formularios preoperacionales', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'conductor', 'description' => 'Usuario conductor, responsable de diligencias formularios preoperacionales', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insertar los datos usando DB facade
        DB::table('roles')->insert($roles);
    }
}
