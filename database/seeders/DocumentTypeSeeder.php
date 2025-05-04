<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentType;
use Carbon\Carbon;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional, pero recomendado para evitar duplicados en ejecuciones repetidas)
        DB::table('document_types')->delete();

        $now = Carbon::now();

        $documentTypes = [
            ['name' => 'Cédula de ciudadanía', 'abbreviation' => 'CC', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tarjeta de identidad', 'abbreviation' => 'TI', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cédula de extranjería', 'abbreviation' => 'CE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Permiso por Protección Temporal', 'abbreviation' => 'PPT', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Permiso Especial de Permanencia', 'abbreviation' => 'PEP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Visa tipo M (Migrante)', 'abbreviation' => 'VISA-M', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Visa tipo R (Residente)', 'abbreviation' => 'VISA-R', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Visa tipo V (Visitante con permiso de trabajo)', 'abbreviation' => 'VISA-V', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pasaporte', 'abbreviation' => 'PAS', 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('document_types')->insert($documentTypes);
    }
}
