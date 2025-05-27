<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\{
    PreoperationalForm,
    User,
    Vehicle,
    Section,
    Question,
    Answer,
    Observation,
    VehicleType,
    Alert
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;

class PreoperationalFormController extends Controller
{
    /**
     * Mostrar listado de formularios preoperacionales con filtros.
     */
    public function index(Request $request)
    {
        $query = PreoperationalForm::with(['user', 'vehicle']);

        if ($request->has('filter_type')) {
            switch ($request->filter_type) {
                case 'user':
                    if ($request->filled('user_search')) {
                        $query->whereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->user_search . '%')
                              ->orWhere('last_name', 'like', '%' . $request->user_search . '%');
                        });
                    }
                    break;

                case 'vehicle':
                    if ($request->filled('vehicle_search')) {
                        $query->whereHas('vehicle', function ($q) use ($request) {
                            $q->where('plate', 'like', '%' . $request->vehicle_search . '%');
                        });
                    }
                    break;

                case 'date_range':
                    if ($request->filled('date_from')) {
                        $query->whereDate('created_at', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->whereDate('created_at', '<=', $request->date_to);
                    }
                    break;
            }
        }

        $preoperationalForms = $query->orderByDesc('created_at')->paginate(20);
        return view('modules.preoperational_form.index', compact('preoperationalForms'));
    }

    /**
     * Mostrar detalles de un formulario preoperacional.
     */
    public function show(PreoperationalForm $preoperationalForm)
    {
        $preoperationalForm->load([
            'user',
            'vehicle.brand',
            'vehicle.model',
            'answers',
            'observations',
        ]);
    
        $tipoVehiculoId = $preoperationalForm->vehicle->vehicle_type_id;

        $sections = Section::with('questions')
        ->whereHas('vehicleTypes', function($q) use ($tipoVehiculoId) {
            $q->where('vehicle_type_id', $tipoVehiculoId);
        })
        ->get();
    
        return view(
            'modules.preoperational_form.show',
            compact('preoperationalForm', 'sections')
        );
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $users = User::whereHas('role', fn ($q) => $q->where('name', 'conductor'))
                     ->where('is_active', true)
                     ->get();
        $vehicleTypes = VehicleType::all();
        $vehicles = Vehicle::where('is_active', true)->get();
        $sections = Section::with('questions')->get();

        return view('modules.preoperational_form.create', compact('users', 'vehicleTypes', 'vehicles', 'sections'));
    }

    /**
     * Almacenar nuevo formulario preoperacional.
     */
    public function store(Request $request)
    {
        // Depuración - Comentar o eliminar en producción
        Log::info('Datos recibidos en store:', $request->all());
        
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'vehicle_id'  => 'required|exists:vehicles,id',
            'new_mileage' => 'required|integer|min:0',
        ]);
        
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if ($request->new_mileage < $vehicle->mileage) {
            return back()->withInput()->withErrors([
                'new_mileage' => 'El nuevo kilometraje debe ser mayor o igual que el último registrado.',
            ]);
        }

        DB::beginTransaction();

        try {
            // Crear el formulario preoperacional
            $form = PreoperationalForm::create([
                'user_id'     => $request->user_id,
                'vehicle_id'  => $request->vehicle_id,
                'new_mileage' => $request->new_mileage,
            ]);

            // Actualizar el kilometraje del vehículo
            $vehicle->update(['mileage' => $request->new_mileage]);

            // Registrar respuestas
            if ($request->has('answers') && is_array($request->answers)) {
                foreach ($request->answers as $questionId => $value) {
                    $answer = new Answer([
                        'form_id'     => $form->id,
                        'question_id' => $questionId,
                        'value'       => $value,
                    ]);
                    $answer->save();

                    // Crear alerta si la respuesta es negativa (0)
                    if ($value == 0) {
                        Alert::create([
                            'form_id'        => $form->id,
                            'answer_id'      => $answer->id,
                            'alert_status_id' => 1,
                        ]);
                    }
                }
            }

            // Registrar observaciones
            if ($request->has('observations') && is_array($request->observations)) {
                foreach ($request->observations as $sectionId => $text) {
                    if (!empty(trim($text))) {
                        if (strlen(trim($text)) < 50) {
                            throw new \Exception('Las observaciones deben tener al menos 50 caracteres.');
                        }

                        $observation = new Observation([
                            'form_id'    => $form->id,
                            'section_id' => $sectionId,
                            'text'       => $text,
                        ]);
                        $observation->save();

                        Alert::create([
                            'form_id'         => $form->id,
                            'observation_id'  => $observation->id,
                            'alert_status_id' => 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route(Auth::user()->role->name . '.preoperational-forms.index')
                ->with('success', 'Formulario preoperacional creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear formulario preoperacional: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear el formulario preoperacional: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(PreoperationalForm $preoperationalForm)
    {
        $users = User::whereHas('role', fn ($q) => $q->where('name', 'conductor'))
                     ->where('is_active', true)
                     ->get();
        $vehicleTypes = VehicleType::all();
        $vehicles = Vehicle::where('is_active', true)->get();
        
        // Cargar las respuestas existentes
        $answers = [];
        foreach ($preoperationalForm->answers as $answer) {
            $answers[$answer->question_id] = $answer->value;
        }
        
        // Cargar las observaciones existentes
        $observations = [];
        foreach ($preoperationalForm->observations as $observation) {
            $observations[$observation->section_id] = $observation->text;
        }
        
        return view('modules.preoperational_form.edit', compact(
            'preoperationalForm', 
            'users', 
            'vehicleTypes', 
            'vehicles', 
            'answers', 
            'observations'
        ));
    }

    /**
     * Actualizar formulario preoperacional.
     */
    public function update(Request $request, PreoperationalForm $preoperationalForm)
    {
        // Depuración - Comentar o eliminar en producción
        Log::info('Datos recibidos en update:', $request->all());
        
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'vehicle_id'  => 'required|exists:vehicles,id',
            'new_mileage' => 'required|integer|min:0',
        ]);
    
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        
        // Verificar que el vehículo seleccionado pertenezca al mismo tipo de vehículo
        if ($vehicle->vehicle_type_id != $preoperationalForm->vehicle->vehicle_type_id) {
            return back()->withInput()->withErrors([
                'vehicle_id' => 'El vehículo seleccionado debe ser del mismo tipo que el original.',
            ]);
        }
        
        $currentMileage = $vehicle->mileage;
    
        // Si el vehículo cambió, verificamos contra el kilometraje actual
        // Si es el mismo vehículo, verificamos contra el kilometraje anterior
        $mileageToCompare = $preoperationalForm->vehicle_id == $request->vehicle_id 
            ? $preoperationalForm->vehicle->mileage - $preoperationalForm->new_mileage + $preoperationalForm->vehicle->mileage
            : $currentMileage;
    
        if ($request->new_mileage < $mileageToCompare) {
            return back()->withInput()->withErrors([
                'new_mileage' => 'El nuevo kilometraje debe ser mayor o igual que el último registrado.',
            ]);
        }
    
        DB::beginTransaction();
    
        try {
            // Actualizar el formulario
            $preoperationalForm->update([
                'user_id'     => $request->user_id,
                'vehicle_id'  => $request->vehicle_id,
                'new_mileage' => $request->new_mileage,
            ]);
    
            // Actualizar kilometraje del vehículo
            $vehicle->update(['mileage' => $request->new_mileage]);
    
            // CAMBIO IMPORTANTE: Primero eliminar las alertas, luego las respuestas y observaciones
            Alert::where('form_id', $preoperationalForm->id)->delete();
            Answer::where('form_id', $preoperationalForm->id)->delete();
            Observation::where('form_id', $preoperationalForm->id)->delete();
    
            // Registrar nuevas respuestas
            foreach ($request->input('answers', []) as $questionId => $value) {
                $answer = Answer::create([
                    'form_id'     => $preoperationalForm->id,
                    'question_id' => $questionId,
                    'value'       => $value,
                ]);
    
                if ($value == 0) {
                    Alert::create([
                        'form_id'       => $preoperationalForm->id,
                        'answer_id'     => $answer->id,
                        'alert_status_id' => 1,
                    ]);
                }
            }
    
            // Registrar nuevas observaciones
            foreach ($request->input('observations', []) as $sectionId => $text) {
                if (!empty(trim($text))) {
                    if (strlen(trim($text)) < 50) {
                        throw new \Exception('Las observaciones deben tener al menos 50 caracteres.');
                    }
    
                    $observation = Observation::create([
                        'form_id'    => $preoperationalForm->id,
                        'section_id' => $sectionId,
                        'text'       => $text,
                    ]);
    
                    Alert::create([
                        'form_id'         => $preoperationalForm->id,
                        'observation_id'  => $observation->id,
                        'alert_status_id' => 1,
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route(Auth::user()->role->name . '.preoperational-forms.index')
                ->with('success', 'Formulario preoperacional actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar el formulario preoperacional: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar formulario preoperacional.
     */
    public function destroy(PreoperationalForm $preoperationalForm)
    {
        try {
            // Primero eliminar las alertas asociadas
            Alert::where('form_id', $preoperationalForm->id)->delete();
            // Luego eliminar respuestas y observaciones
            Answer::where('form_id', $preoperationalForm->id)->delete();
            Observation::where('form_id', $preoperationalForm->id)->delete();
            $preoperationalForm->delete();

            return redirect()->route(Auth::user()->role->name . '.preoperational-forms.index')
                ->with('success', 'Formulario preoperacional eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el formulario preoperacional: ' . $e->getMessage());
        }
    }
}
