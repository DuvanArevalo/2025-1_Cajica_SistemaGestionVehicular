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
            ['name' => 'Interior del Vehículo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Exterior del Vehículo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mecánica del Vehículo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sistema de Seguridad y Accesorios', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Revisión de Documentación', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Observaciones Generales', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Insertar las secciones
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}