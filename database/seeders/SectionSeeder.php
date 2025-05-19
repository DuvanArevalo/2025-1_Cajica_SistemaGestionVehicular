<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $sections = [
            // Secciones generales para vehículos
            ['name' => 'Interior del Vehículo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Exterior del Vehículo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mecánica del Vehículo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sistema de Seguridad y Accesorios', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Revisión de Documentación', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Observaciones Generales', 'created_at' => $now, 'updated_at' => $now],
            
            // Secciones específicas para motocicletas
            ['name' => 'Estado General del Chasis', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Luces y Señalización', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sistema de Transmisión', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ruedas y Suspensión', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Frenos', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sistema de Escape', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Equipamiento y Accesorios', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Documentación y Seguridad', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insertar las secciones
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}