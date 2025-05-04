<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            UserSeeder::class,          // Finalmente los usuarios (que dependen de los anteriores)
            
        ]);
    }
}
