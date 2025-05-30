<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,          // Primero los roles
            DocumentTypeSeeder::class,  // Luego los tipos de documento
            UserSeeder::class,          // Luego usuarios (que dependen de los anteriores)
            
            // Otros seeders
            AlertStatusSeeder::class,
            SectionSeeder::class,
            VehicleTypeSeeder::class,
            BrandSeeder::class,
            VehicleModelSeeder::class,
            VehicleSeeder::class,
            QuestionSeeder::class,      // Añadimos el seeder de preguntas
            SectionVehicleTypeSeeder::class, // Añadimos el seeder de relación sección-tipo de vehículo
        ]);
    }
}
