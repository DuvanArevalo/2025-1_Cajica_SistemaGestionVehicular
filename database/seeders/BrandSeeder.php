<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional, pero recomendado para evitar duplicados en ejecuciones repetidas)
        DB::table('brands')->delete();

        $now = Carbon::now();

        $brands = [
            ['name' => 'Toyota', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chevrolet', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Renault', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mazda', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nissan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ford', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kia', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hyundai', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Suzuki', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'BMW', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
