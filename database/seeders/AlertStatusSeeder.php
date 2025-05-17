<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AlertStatus;
use Carbon\Carbon;

class AlertStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['type' => 'Notificado', 'description' => 'Alerta generada y notificada'],
            ['type' => 'En revisión', 'description' => 'Alerta en proceso de revisión'],
            ['type' => 'Atendido', 'description' => 'Alerta atendida y resuelta'],
            ['type' => 'Cerrado', 'description' => 'Alerta cerrada sin necesidad de acción'],
        ];

        foreach ($statuses as $status) {
            AlertStatus::create($status);
        }
    }
}