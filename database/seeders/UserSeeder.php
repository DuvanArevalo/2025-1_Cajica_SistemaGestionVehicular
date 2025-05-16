<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\DocumentType;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Desactivar revisión de claves foráneas
        DB::table('users')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Reactivar revisión

        // --- Obtener IDs necesarios ---
        // Buscar roles por nombre (más robusto que usar IDs directamente)
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $sstRole = Role::where('name', 'sst')->firstOrFail();
        $conductorRole = Role::where('name', 'conductor')->firstOrFail();

        // Buscar un tipo de documento por abreviatura (ej. Cédula de Ciudadanía)
        $docTypeCC = DocumentType::where('abbreviation', 'CC')->firstOrFail();

        // --- Crear Usuarios ---

        // Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@epc.com'], // Clave única para buscar/crear
            [
                'name1' => 'Super',
                'lastname1' => 'Admin',
                'password' => Hash::make('Admin123.'),
                'document_type_id' => $docTypeCC->id,
                'document_number' => '1000000001',
                'role_id' => $adminRole->id,
                'is_active' => true,
                // 'name2', 'lastname2' son nullables
            ]
        );

        // Usuario SST
        User::updateOrCreate(
            ['email' => 'sst@epc.com'],
            [
                'name1' => 'Salud',
                'lastname1' => 'Seguridad',
                'password' => Hash::make('Seguridad123.'),
                'document_type_id' => $docTypeCC->id,
                'document_number' => '1000000002', 
                'role_id' => $sstRole->id,
                'is_active' => true,
            ]
        );

        // Usuario Conductor
        User::updateOrCreate(
            ['email' => 'conductor@epc.com'],
            [
                'name1' => 'Carlos',
                'lastname1' => 'Conductor',
                'password' => Hash::make('Conductor123.'),
                'document_type_id' => $docTypeCC->id,
                'document_number' => '1000000003',
                'role_id' => $conductorRole->id,
                'is_active' => true,
            ]
        );
    }
}