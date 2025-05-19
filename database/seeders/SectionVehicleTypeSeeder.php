<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use App\Models\VehicleType;

class SectionVehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla pivot antes de insertar nuevos registros
        DB::table('section_vehicle_type')->delete();
        
        // Obtener tipos de vehículos específicos
        $motoType = VehicleType::where('name', 'Motocicleta')->first();
        $autoType = VehicleType::where('name', 'Automóvil')->first();
        $camionetaType = VehicleType::where('name', 'Camioneta')->first();
        
        // Obtener secciones para automóvil y camioneta
        $carSections = Section::whereIn('name', [
            'Exterior del Vehículo',
            'Mecánica del Vehículo',
            'Sistema de Seguridad y Accesorios',
            'Revisión de Documentación',
            'Observaciones Generales',
            'Interior del Vehículo'
        ])->get();
        
        // Obtener secciones específicas para motocicletas
        $motoSections = Section::whereIn('name', [
            'Estado General del Chasis',
            'Luces y Señalización',
            'Sistema de Transmisión',
            'Ruedas y Suspensión',
            'Frenos',
            'Sistema de Escape',
            'Equipamiento y Accesorios',
            'Documentación y Seguridad'
        ])->get();
        
        // Relacionar secciones de automóvil con Automóvil y Camioneta
        if ($autoType) {
            foreach ($carSections as $section) {
                DB::table('section_vehicle_type')->insert([
                    'section_id' => $section->id,
                    'vehicle_type_id' => $autoType->id,
                ]);
            }
        }
        
        if ($camionetaType) {
            foreach ($carSections as $section) {
                DB::table('section_vehicle_type')->insert([
                    'section_id' => $section->id,
                    'vehicle_type_id' => $camionetaType->id,
                ]);
            }
        }
        
        // Relacionar secciones específicas de motocicletas con el tipo "Motocicleta"
        if ($motoType) {
            foreach ($motoSections as $section) {
                DB::table('section_vehicle_type')->insert([
                    'section_id' => $section->id,
                    'vehicle_type_id' => $motoType->id,
                ]);
            }
        }
    }
}
