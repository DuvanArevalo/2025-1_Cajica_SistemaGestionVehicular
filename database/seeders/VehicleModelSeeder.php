<?php

namespace Database\Seeders;

use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional, pero recomendado para evitar duplicados en ejecuciones repetidas)
        DB::table('vehicles_models')->delete();

        $now = Carbon::now();
        
        $vehicleModels = [
            ['brand_id' => 1, 'name' => 'Corolla', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 1, 'name' => 'Hilux', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 2, 'name' => 'Spark', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 2, 'name' => 'Captiva', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 3, 'name' => 'Logan', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 3, 'name' => 'Duster', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 4, 'name' => 'CX-5', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 4, 'name' => 'Mazda 3', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 5, 'name' => 'Sentra', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 5, 'name' => 'Frontier', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 6, 'name' => 'Fiesta', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 6, 'name' => 'Ranger', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 7, 'name' => 'Rio', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 7, 'name' => 'Sportage', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 8, 'name' => 'Accent', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 8, 'name' => 'Tucson', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 9, 'name' => 'gixxer-150', 'created_at' => $now, 'updated_at' => $now],
            ['brand_id' => 10, 'name' => 'cbr-190', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($vehicleModels as $model) {
            VehicleModel::create($model);
        }
    }
}