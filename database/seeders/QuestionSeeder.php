<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Question;
use App\Models\Section;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional, pero recomendado para evitar duplicados en ejecuciones repetidas)
        DB::table('questions')->delete();

        $now = Carbon::now();

        // Obtener las secciones
        $interiorSection = Section::where('name', 'Interior del Vehículo')->first();
        $exteriorSection = Section::where('name', 'Exterior del Vehículo')->first();
        $mecanicaSection = Section::where('name', 'Mecánica del Vehículo')->first();
        $seguridadSection = Section::where('name', 'Sistema de Seguridad y Accesorios')->first();
        $documentacionSection = Section::where('name', 'Revisión de Documentación')->first();
        $observacionesSection = Section::where('name', 'Observaciones Generales')->first();
        
        // Obtener las secciones específicas para motocicletas
        $chasisSection = Section::where('name', 'Estado General del Chasis')->first();
        $lucesSection = Section::where('name', 'Luces y Señalización')->first();
        $transmisionSection = Section::where('name', 'Sistema de Transmisión')->first();
        $ruedasSection = Section::where('name', 'Ruedas y Suspensión')->first();
        $frenosSection = Section::where('name', 'Frenos')->first();
        $escapeSection = Section::where('name', 'Sistema de Escape')->first();
        $equipamientoSection = Section::where('name', 'Equipamiento y Accesorios')->first();
        $docSeguridadSection = Section::where('name', 'Documentación y Seguridad')->first();

        // Preguntas para cada sección
        $questions = [
            // Interior del Vehículo
            [
                'text' => '¿Los cinturones de seguridad están en buen estado y funcionan correctamente?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$interiorSection]
            ],
            [
                'text' => '¿El tablero y los controles internos presentan daños, faltantes o mal funcionamiento?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$interiorSection]
            ],
            
            // Exterior del Vehículo
            [
                'text' => '¿La carrocería presenta abolladuras, rayones o signos de corrosión?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$exteriorSection]
            ],
            [
                'text' => '¿Los espejos, faros y luces exteriores están completos y en funcionamiento?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$exteriorSection]
            ],
            
            // Mecánica del Vehículo
            [
                'text' => '¿El motor arranca sin dificultades y no presenta ruidos anormales?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$mecanicaSection]
            ],
            [
                'text' => '¿Se detectan fugas de aceite, refrigerante o fluidos bajo el vehículo?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$mecanicaSection]
            ],
            
            // Sistema de Seguridad y Accesorios
            [
                'text' => '¿Los frenos responden correctamente y no hacen ruidos extraños?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$seguridadSection]
            ],
            [
                'text' => '¿El extintor, triángulos de seguridad y botiquín están presentes y en condiciones?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$seguridadSection]
            ],
            
            // Revisión de Documentación
            [
                'text' => '¿La tarjeta de propiedad y SOAT están vigentes y disponibles?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$documentacionSection]
            ],
            [
                'text' => '¿El certificado de revisión técnico-mecánica se encuentra al día?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$documentacionSection]
            ],
            
            // Observaciones Generales
            [
                'text' => '¿El olor o comportamiento durante la prueba de manejo es normal?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$observacionesSection]
            ],
            
            // Estado General del Chasis (Motocicletas)
            [
                'text' => '¿El chasis presenta grietas, soldaduras no originales o deformaciones?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$chasisSection]
            ],
            [
                'text' => '¿Los soportes del motor y otros elementos estructurales están firmes y sin daño?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$chasisSection]
            ],
            
            // Luces y Señalización (Motocicletas)
            [
                'text' => '¿Funcionan correctamente el faro delantero, trasero y la luz de freno?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$lucesSection]
            ],
            [
                'text' => '¿Los intermitentes responden adecuadamente al activar las direccionales?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$lucesSection]
            ],
            
            // Sistema de Transmisión (Motocicletas)
            [
                'text' => '¿La cadena o banda de transmisión tiene la tensión adecuada y sin signos de desgaste excesivo?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$transmisionSection]
            ],
            [
                'text' => '¿Las coronas y piñones están alineados y sin dientes rotos o desgastados?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$transmisionSection]
            ],
            
            // Ruedas y Suspensión (Motocicletas)
            [
                'text' => '¿Las llantas no tienen fisuras, golpes o signos de desgaste irregular?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$ruedasSection]
            ],
            [
                'text' => '¿La suspensión delantera y trasera responde sin ruidos anormales ni rebotes excesivos?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$ruedasSection]
            ],
            
            // Frenos (Motocicletas)
            [
                'text' => '¿El freno delantero y trasero responden correctamente sin hundimiento en las manetas o pedales?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$frenosSection]
            ],
            [
                'text' => '¿No se evidencian fugas en los sistemas hidráulicos ni desgaste excesivo en discos o pastillas?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$frenosSection]
            ],
            
            // Sistema de Escape (Motocicletas)
            [
                'text' => '¿El tubo de escape está bien fijado y no presenta perforaciones ni fugas?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$escapeSection]
            ],
            [
                'text' => '¿El nivel de ruido del motor se mantiene dentro de los parámetros normales?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$escapeSection]
            ],
            
            // Equipamiento y Accesorios (Motocicletas)
            [
                'text' => '¿Los espejos, porta placas y maniguetas están completos y firmes?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$equipamientoSection]
            ],
            [
                'text' => '¿El velocímetro, cuentakilómetros y otros indicadores funcionan correctamente?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$equipamientoSection]
            ],
            
            // Documentación y Seguridad (Motocicletas)
            [
                'text' => '¿La moto cuenta con tarjeta de propiedad, SOAT y revisión técnico-mecánica vigentes?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$docSeguridadSection]
            ],
            [
                'text' => '¿Se encuentra presente el casco reglamentario y chaleco reflectivo?',
                'created_at' => $now,
                'updated_at' => $now,
                'sections' => [$docSeguridadSection]
            ],
        ];

        // Insertar las preguntas y asociarlas a las secciones
        foreach ($questions as $questionData) {
            $sections = $questionData['sections'];
            unset($questionData['sections']);
            
            $question = Question::create($questionData);
            
            // Asociar la pregunta con las secciones
            foreach ($sections as $section) {
                if ($section) {
                    DB::table('question_section')->insert([
                        'question_id' => $question->id,
                        'section_id' => $section->id,
                    ]);
                }
            }
        }
    }
}
