<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Observation;
use App\Models\PreoperationalForm;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ObservationController extends Controller
{
    /**
     * Display a listing of the observations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Observation::with(['form.vehicle', 'section']);

        // Filtrar por usuario si es conductor
        if (strtolower(Auth::user()->role->name) === 'conductor') {
            $query->whereHas('form', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // Aplicar filtros según el tipo seleccionado
        if ($request->filled('filter_type')) {
            switch ($request->filter_type) {
                case 'text':
                    if ($request->filled('text_search')) {
                        $query->where('text', 'like', '%' . $request->text_search . '%');
                    }
                    break;
                case 'form':
                    if ($request->filled('form_search')) {
                        $query->whereHas('form', function ($q) use ($request) {
                            $q->where('id', 'like', '%' . $request->form_search . '%')
                              ->orWhereHas('vehicle', function ($vq) use ($request) {
                                  $vq->where('license_plate', 'like', '%' . $request->form_search . '%');
                              });
                        });
                    }
                    break;
                case 'section':
                    if ($request->filled('section_search')) {
                        $query->whereHas('section', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->section_search . '%');
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

        // Ordenar por fecha de creación descendente (más recientes primero)
        $query->orderBy('created_at', 'desc');

        // Paginar los resultados
        $observations = $query->paginate(20);

        return view('modules.observation.index', compact('observations'));
    }

    /**
     * Show the form for creating a new observation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtener todos los formularios preoperacionales con sus relaciones
        $forms = PreoperationalForm::with('vehicle.vehicleType.sections')->get();
        
        // Ya no necesitamos cargar todas las secciones, se cargarán dinámicamente
        // según el tipo de vehículo seleccionado
        
        return view('modules.observation.create', compact('forms'));
    }

    /**
     * Store a newly created observation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'form_id' => 'required|exists:preoperational_forms,id',
            'section_id' => 'required|exists:sections,id',
            'text' => 'required|string|min:50|max:500',
        ], [
            'form_id.required' => 'El formulario es obligatorio.',
            'form_id.exists' => 'El formulario seleccionado no existe.',
            'section_id.required' => 'La sección es obligatoria.',
            'section_id.exists' => 'La sección seleccionada no existe.',
            'text.required' => 'La observación es obligatoria.',
            'text.min' => 'La observación debe tener al menos 50 caracteres.',
            'text.max' => 'La observación no puede exceder los 500 caracteres.',
        ]);

        try {
            // Cargar información del formulario y sección para mensajes más específicos
            $form = PreoperationalForm::with('vehicle')->findOrFail($validated['form_id']);
            $section = Section::findOrFail($validated['section_id']);
            
            // Verificar si ya existe una observación para este formulario y sección
            $existingObservation = Observation::where('form_id', $validated['form_id'])
                ->where('section_id', $validated['section_id'])
                ->first();
            
            // Información para mensajes
            $formIdentifier = "Formulario #{$form->id} - {$form->vehicle->plate}";
            $sectionName = $section->name;
            
            if ($existingObservation) {
                // Actualizar la observación existente
                $existingObservation->update([
                    'text' => $validated['text'],
                ]);
                
                return redirect()
                    ->route(strtolower(Auth::user()->role->name) . '.observations.index')
                    ->with('warning', "¡Atención! Ya existía una observación para la sección \"{$sectionName}\" del {$formIdentifier} y ha sido actualizada.");
            } else {
                // Crear una nueva observación
                $observation = new Observation();
                $observation->form_id = $validated['form_id'];
                $observation->section_id = $validated['section_id'];
                $observation->text = $validated['text'];
                $observation->save();
                
                return redirect()
                    ->route(strtolower(Auth::user()->role->name) . '.observations.index')
                    ->with('success', "Observación creada exitosamente para la sección \"{$sectionName}\" del {$formIdentifier}.");
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al procesar la observación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified observation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Obtener la observación con sus relaciones
        $observation = Observation::with(['form.vehicle', 'section', 'alerts'])
            ->findOrFail($id);

        return view('modules.observation.show', compact('observation'));
    }

    /**
     * Show the form for editing the specified observation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Obtener la observación a editar
        $observation = Observation::findOrFail($id);
        
        // Obtener el formulario preoperacional con el vehículo y su tipo
        $form = PreoperationalForm::with('vehicle.vehicleType.sections')->findOrFail($observation->form_id);
        
        // Obtener todas las secciones disponibles para referencia
        $sections = Section::all();

        return view('modules.observation.edit', compact('observation', 'form', 'sections'));
    }

    /**
     * Update the specified observation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'form_id' => 'required|exists:preoperational_forms,id',
            'section_id' => 'required|exists:sections,id',
            'text' => 'required|string|min:50|max:500',
        ], [
            'form_id.required' => 'El formulario es obligatorio.',
            'form_id.exists' => 'El formulario seleccionado no existe.',
            'section_id.required' => 'La sección es obligatoria.',
            'section_id.exists' => 'La sección seleccionada no existe.',
            'text.required' => 'La observación es obligatoria.',
            'text.min' => 'La observación debe tener al menos 50 caracteres.',
            'text.max' => 'La observación no puede exceder los 500 caracteres.',
        ]);

        try {
            // Cargar información del formulario y sección para mensajes más específicos
            $form = PreoperationalForm::with('vehicle')->findOrFail($validated['form_id']);
            $section = Section::findOrFail($validated['section_id']);
            
            // Actualizar la observación
            $observation = Observation::findOrFail($id);
            $observation->form_id = $validated['form_id'];
            $observation->section_id = $validated['section_id'];
            $observation->text = $validated['text'];
            $observation->save();
            
            // Información para mensajes
            $formIdentifier = "Formulario #{$form->id} - {$form->vehicle->plate}";
            $sectionName = $section->name;

            return redirect()
                ->route(strtolower(Auth::user()->role->name) . '.observations.index')
                ->with('success', "Observación actualizada exitosamente para la sección \"{$sectionName}\" del {$formIdentifier}.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar la observación: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified observation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Verificar si la observación tiene alertas asociadas
            $observation = Observation::withCount('alerts')->findOrFail($id);
            
            if ($observation->alerts_count > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'No se puede eliminar la observación porque tiene alertas asociadas.');
            }
            
            // Eliminar la observación
            $observation->delete();
            
            return redirect()
                ->route(strtolower(Auth::user()->role->name) . '.observations.index')
                ->with('success', 'Observación eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar la observación: ' . $e->getMessage());
        }
    }
}