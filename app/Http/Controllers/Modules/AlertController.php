<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\AlertStatus;
use App\Models\PreoperationalForm;
use App\Models\Section;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Observation;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.

     */
    public function index()
    {
        //
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Alert::with(['preoperationalForm', 'answer', 'observation', 'alertStatus']);

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

     */
    public function create()
    {
        //
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $preoperationalForms = PreoperationalForm::all();
        $alertStatuses = AlertStatus::all();
        
        return view('modules.alert.create', compact('preoperationalForms', 'alertStatuses'));
    }

    /**
     * Store a newly created resource in storage.

     */
    public function store(Request $request)
    {
        //
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:preoperational_forms,id',
            'section_id' => 'required|exists:sections,id',
            'question_id' => 'nullable|exists:questions,id',
            'answer_value' => 'nullable|boolean',
            'observation_text' => 'nullable|required_if:answer_value,0|string',
            'alert_status_id' => 'required|exists:alert_statuses,id',
        ]);

        // Si hay una observación, crearla primero
        $observation_id = null;
        if ($request->has('observation_text') && !empty($request->observation_text)) {
            $observation = Observation::create([
                'description' => $request->observation_text
            ]);
            $observation_id = $observation->id;
        }

        // Crear la alerta
        Alert::create([
            'form_id' => $request->form_id,
            'answer_id' => $request->answer_value !== null ? 
                Answer::where('value', $request->answer_value)->first()->id : null,
            'observation_id' => $observation_id,
            'alert_status_id' => $request->alert_status_id,
        ]);

        return redirect()->route(Auth::user()->role->name . '.alerts.index')
            ->with('success', 'Alerta creada correctamente');
    }

    /**
     * Display the specified resource.

     */
    public function show(Alert $alert)
    {
        //
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = Alert::with(['preoperationalForm', 'answer', 'observation', 'alertStatus'])->findOrFail($id);
        return view('modules.alert.show', compact('alert'));
    }

    /**
     * Show the form for editing the specified resource.

     */
    public function edit(Alert $alert)
    {
        //
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alert = Alert::with(['preoperationalForm', 'answer', 'observation', 'alertStatus'])->findOrFail($id);
        $alertStatuses = AlertStatus::all();
        
        return view('modules.alert.edit', compact('alert', 'alertStatuses'));
    }

    /**
     * Update the specified resource in storage.

     */
    public function update(Request $request, Alert $alert)
    {
        //
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

        return redirect()->route(Auth::user()->role->name . '.alerts.index')
            ->with('success', 'Estado de alerta actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Alert $alert)
    {
        //
    public function destroy($id)
    {
        // Esta función no debe estar disponible para ningún rol
        // pero implementamos la lógica correcta por si acaso
        $alert = Alert::findOrFail($id);
        
        if ($alert->preoperationalForm) {
            return redirect()->route(Auth::user()->role->name . '.alerts.index')
                ->with('error', 'No se puede eliminar esta Alerta porque hay formularios preoperacionales asociados.');
        }

        $alert->delete();
        return redirect()->route(Auth::user()->role->name . '.alerts.index')
            ->with('success', 'Alerta eliminada exitosamente.');
    }

    /**
     * Obtiene los datos del formulario preoperacional
     */
    public function getFormData($id)
    {
        $form = PreoperationalForm::with(['vehicle', 'user'])->findOrFail($id);
        
        return response()->json([
            'driver_name' => $form->user->name,
            'vehicle' => $form->vehicle->plate
        ]);
    }

    /**
     * Obtiene las secciones disponibles para un tipo de vehículo
     */
    public function getSections($vehicleTypeId)
    {
        $sections = Section::where('vehicle_type_id', $vehicleTypeId)->get();
        return response()->json($sections);
    }

    /**
     * Obtiene las preguntas disponibles para una sección
     */
    public function getQuestions($sectionId)
    {
        $questions = Question::where('section_id', $sectionId)->get();
        return response()->json($questions);
    }
}
