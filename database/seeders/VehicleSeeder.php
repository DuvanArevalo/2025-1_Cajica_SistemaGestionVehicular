<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional, pero recomendado para evitar duplicados en ejecuciones repetidas)
        DB::table('vehicles')->delete();

        $now = Carbon::now();
        
        $vehicles = [
            [
                'vehicle_type_id' => 1, // Automóvil
                'brand_id' => 1, // Toyota
                'model_id' => 1, // Corolla
                'model_year' => '2020',
                'wheel_count' => '4',
                'color' => 'Blanco',
                'plate' => 'ABC123',
                'mileage' => 15000,
                'is_active' => true,
                'soat' => $now->addMonths(6)->toDateTimeString(),
                'soat_status' => true,
                'mechanical_review' => $now->addMonths(8)->toDateTimeString(),
                'mechanical_review_status' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'vehicle_type_id' => 2, // Camioneta
                'brand_id' => 2, // Chevrolet
                'model_id' => 3, // Spark
                'model_year' => '2019',
                'wheel_count' => '4',
                'color' => 'Rojo',
                'plate' => 'DEF456',
                'mileage' => 25000,
                'is_active' => true,
                'soat' => $now->addMonths(3)->toDateTimeString(),
                'soat_status' => true,
                'mechanical_review' => $now->addMonths(5)->toDateTimeString(),
                'mechanical_review_status' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'vehicle_type_id' => 3, // Camión
                'brand_id' => 3, // Renault
                'model_id' => 6, // Duster
                'model_year' => '2021',
                'wheel_count' => '6',
                'color' => 'Azul',
                'plate' => 'GHI789',
                'mileage' => 35000,
                'is_active' => true,
                'soat' => $now->addMonths(9)->toDateTimeString(),
                'soat_status' => true,
                'mechanical_review' => $now->addMonths(10)->toDateTimeString(),
                'mechanical_review_status' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'vehicle_type_id' => 4, // Motocicleta
                'brand_id' => 9, // Suzuki
                'model_id' => 17, // gixxer-150
                'model_year' => '2022',
                'wheel_count' => '2',
                'color' => 'Azul',
                'plate' => 'ABC12D',
                'mileage' => 5000,
                'is_active' => true,
                'soat' => $now->addMonths(8)->toDateTimeString(),
                'soat_status' => true,
                'mechanical_review' => $now->addMonths(10)->toDateTimeString(),
                'mechanical_review_status' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'vehicle_type_id' => 4, // Motocicleta
                'brand_id' => 10, // BMW (en lugar de Honda, ya que Honda no está en la lista)
                'model_id' => 18, // cbr-190
                'model_year' => '2021',
                'wheel_count' => '2',
                'color' => 'Rojo',
                'plate' => 'XYZ98E',
                'mileage' => 8000,
                'is_active' => true,
                'soat' => $now->addMonths(5)->toDateTimeString(),
                'soat_status' => true,
                'mechanical_review' => $now->addMonths(7)->toDateTimeString(),
                'mechanical_review_status' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
