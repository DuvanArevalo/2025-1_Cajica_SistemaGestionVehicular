<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\AlertStatus;
use App\Models\Answer;
use App\Models\PreoperationalForm;
use App\Models\Observation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Alert::with(['preoperationalForm', 'answer', 'observation', 'alertStatus'])
            ->select('*')
            ->selectRaw('CASE 
                WHEN answer_id IS NOT NULL THEN "Respuesta" 
                WHEN observation_id IS NOT NULL THEN "Observación" 
                ELSE "Desconocido" 
            END as alert_type');

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'form';

        // Filtro por formulario
        if ($filterType == 'form' && $request->filled('form_search')) {
            $buscado = trim($request->form_search);
            $query->whereHas('preoperationalForm', function($q) use ($buscado) {
                $q->where('id', $buscado);
            });
        }

        // Filtro por estado
        if ($filterType == 'status' && $request->filled('status_search')) {
            $buscado = strtolower(trim($request->status_search));
            $query->whereHas('alertStatus', function($q) use ($buscado) {
                $q->whereRaw('LOWER(type) LIKE ?', ["%{$buscado}%"]);
            });
        }

        // Filtro por rango de fechas
        if ($filterType == 'date_range') {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        // Paginamos y mantenemos los filtros en la URL
        $alerts = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'form_search', 'status_search', 'date_from', 'date_to']));

        return view('modules.alert.index', compact('alerts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $forms = PreoperationalForm::with([
            'vehicle.vehicleType.sections' => function($query) {
                $query->with(['questions' => function($q) {
                    $q->whereDoesntHave('answers', function($subQ) {
                        $subQ->where('value', false);
                    });
                }]);
            }
        ])->get();
        
        $alertStatuses = AlertStatus::all();
        
        return view('modules.alert.create', compact('forms', 'alertStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:preoperational_forms,id',
            'section_id' => 'required|exists:sections,id',
            'alert_status_id' => 'required|exists:alert_statuses,id',
            'alert_type' => 'required|in:question,observation',
        ], [
            'form_id.required' => 'El formulario es obligatorio.',
            'form_id.exists' => 'El formulario seleccionado no existe.',
            'section_id.required' => 'La sección es obligatoria.',
            'section_id.exists' => 'La sección seleccionada no existe.',
            'alert_status_id.required' => 'El estado de la alerta es obligatorio.',
            'alert_status_id.exists' => 'El estado de alerta seleccionado no existe.',
            'alert_type.required' => 'El tipo de alerta es obligatorio.',
            'alert_type.in' => 'El tipo de alerta debe ser pregunta u observación.',
        ]);
    
        // Validaciones adicionales según el tipo de alerta
        if ($request->alert_type === 'question') {
            $request->validate([
                'question_id' => 'required|exists:questions,id',
            ], [
                'question_id.required' => 'La pregunta es obligatoria.',
                'question_id.exists' => 'La pregunta seleccionada no existe.',
            ]);
        } else {
            $request->validate([
                'observation_text' => 'required|string|min:50',
            ], [
                'observation_text.required' => 'La observación es obligatoria.',
                'observation_text.min' => 'La observación debe tener al menos 50 caracteres.',
            ]);
        }
    
        DB::beginTransaction();
        try {
            $alert = new Alert();
            $alert->form_id = $request->form_id;
            $alert->alert_status_id = $request->alert_status_id;
            
            if ($request->alert_type === 'question') {
                // Crear respuesta negativa
                $answer = new Answer();
                $answer->form_id = $request->form_id;
                $answer->question_id = $request->question_id;
                $answer->value = false; // Respuesta negativa
                $answer->save();
                
                $alert->answer_id = $answer->id;
            } else {
                // Crear observación
                $observation = new Observation();
                $observation->form_id = $request->form_id;
                $observation->section_id = $request->section_id;
                $observation->text = $request->observation_text;
                $observation->save();
                
                $alert->observation_id = $observation->id;
            }
            
            $alert->save();
            
            DB::commit();
            
            return redirect()->route(Auth::user()->role->name . '.alerts.index')
                ->with('success', 'Alerta creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear la alerta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = Alert::with(['preoperationalForm', 'preoperationalForm.vehicle', 'answer', 'answer.question', 'observation', 'alertStatus'])
            ->findOrFail($id);
            
        return view('modules.alert.show', compact('alert'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alert = Alert::with(['preoperationalForm', 'answer', 'observation', 'alertStatus'])
            ->findOrFail($id);
        $alertStatuses = AlertStatus::all();
        
        return view('modules.alert.edit', compact('alert', 'alertStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'alert_status_id' => 'required|exists:alert_statuses,id',
        ]);

        $alert = Alert::findOrFail($id);
        $alert->alert_status_id = $request->alert_status_id;
        $alert->save();
        
        return redirect()->route(Auth::user()->role->name . '.alerts.index')->with('success', 'Estado de alerta actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $alert = Alert::findOrFail($id);
            $alert->delete();
            
            return redirect()->route(Auth::user()->role->name . '.alerts.index')->with('success', 'Alerta eliminada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la alerta: ' . $e->getMessage());
        }
    }
}
