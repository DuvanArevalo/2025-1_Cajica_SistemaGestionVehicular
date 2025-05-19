<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar la tabla antes de insertar
        DB::table('vehicle_types')->delete();
        
        $now = Carbon::now();

        $vehicleTypes = [
            [
                'name' => 'Automóvil',
                'description' => 'Vehículo de cuatro ruedas destinado al transporte de personas, con capacidad hasta para nueve plazas, incluido el conductor.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Camioneta',
                'description' => 'Vehículo automotor de cuatro ruedas, con capacidad de carga y transporte de pasajeros.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Camión',
                'description' => 'Vehículo automotor destinado al transporte de carga con un peso bruto vehicular mayor a 3.5 toneladas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Motocicleta',
                'description' => 'Vehículo automotor de dos ruedas en línea.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Autobús',
                'description' => 'Vehículo automotor de transporte público colectivo.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Vehículo Eléctrico',
                'description' => 'Vehículo propulsado por uno o más motores eléctricos.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Vehículo Híbrido',
                'description' => 'Vehículo que combina un motor de combustión interna con uno o más motores eléctricos.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Vehículo de Carga Pesada',
                'description' => 'Vehículo automotor destinado al transporte de carga con un peso bruto vehicular mayor a 10 toneladas.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Vehículo Oficial',
                'description' => 'Vehículo de propiedad de entidades públicas del orden nacional, departamental o municipal.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Vehículo de Emergencia',
                'description' => 'Vehículo automotor debidamente identificado e iluminado, destinado a movilizar personas afectadas por un accidente o catástrofe.',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create($vehicleType);
        }
    }
}