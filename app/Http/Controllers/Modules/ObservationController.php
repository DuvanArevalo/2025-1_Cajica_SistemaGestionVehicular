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
        $observations = $query->paginate(10);

        return view('modules.observation.index', compact('observations'));
    }

    /**
     * Show the form for creating a new observation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtener todos los formularios preoperacionales
        $forms = PreoperationalForm::with('vehicle')->get();
        
        // Obtener todas las secciones disponibles
        $sections = Section::all();

        return view('modules.observation.create', compact('forms', 'sections'));
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
            'form_id' => 'required|exists:forms,id',
            'section_id' => 'required|exists:sections,id',
            'text' => 'required|string|max:500',
        ]);

        try {
            // Crear la nueva observación
            $observation = new Observation();
            $observation->form_id = $validated['form_id'];
            $observation->section_id = $validated['section_id'];
            $observation->text = $validated['text'];
            $observation->save();

            return redirect()
                ->route(strtolower(Auth::user()->role->name) . '.observations.index')
                ->with('success', 'Observación creada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear la observación: ' . $e->getMessage());
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
        
        // Obtener todos los formularios preoperacionales
        $forms = PreoperationalForm::with('vehicle')->get();
        
        // Obtener todas las secciones disponibles
        $sections = Section::all();

        return view('modules.observation.edit', compact('observation', 'forms', 'sections'));
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
            'form_id' => 'required|exists:forms,id',
            'section_id' => 'required|exists:sections,id',
            'text' => 'required|string|max:500',
        ]);

        try {
            // Actualizar la observación
            $observation = Observation::findOrFail($id);
            $observation->form_id = $validated['form_id'];
            $observation->section_id = $validated['section_id'];
            $observation->text = $validated['text'];
            $observation->save();

            return redirect()
                ->route(strtolower(Auth::user()->role->name) . '.observations.index')
                ->with('success', 'Observación actualizada correctamente.');
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

    /**
     * API endpoint to get sections for a specific form.
     *
     * @param  int  $formId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSectionsForForm($formId)
    {
        try {
            // Obtener el formulario con su vehículo
            $form = PreoperationalForm::with('vehicle.vehicleType')->findOrFail($formId);
            
            // Obtener el tipo de vehículo
            $vehicleTypeId = $form->vehicle->vehicleType->id;
            
            // Obtener las secciones asociadas a este tipo de vehículo
            $sections = Section::whereHas('vehicleTypes', function ($query) use ($vehicleTypeId) {
                $query->where('vehicle_type_id', $vehicleTypeId);
            })->get();
            
            return response()->json($sections);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}