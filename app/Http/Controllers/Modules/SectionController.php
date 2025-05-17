<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Iniciar la consulta base
        $query = Section::with('vehicleTypes', 'questions');
        
        // Obtener el tipo de filtro (por defecto 'name')
        $filterType = $request->input('filter_type', 'name');
        
        // Aplicar filtros según el tipo seleccionado
        if ($filterType == 'name' && $request->filled('name_search')) {
            $buscado = strtolower(trim($request->name_search));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
        }
        
        // Filtro por tipo de vehículo
        if ($filterType == 'vehicle_type' && $request->filled('vehicle_type_search')) {
            $buscado = strtolower(trim($request->vehicle_type_search));
            $query->whereHas('vehicleTypes', function($q) use ($buscado) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
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
        $sections = $query
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search', 'vehicle_type_search', 'date_from', 'date_to']));
            
        return view('modules.section.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicleTypes = VehicleType::all();
        return view('modules.section.create', compact('vehicleTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:sections,name',
            'vehicle_types' => 'required|array',
            'vehicle_types.*' => 'exists:vehicle_types,id',
        ], [
            'name.required' => 'El nombre de la sección es obligatorio.',
            'name.unique' => 'Ya existe una sección con este nombre.',
            'vehicle_types.required' => 'Debe seleccionar al menos un tipo de vehículo.',
            'vehicle_types.*.exists' => 'Uno de los tipos de vehículo seleccionados no existe.',
        ]);

        $section = Section::create([
            'name' => $request->name,
        ]);

        $section->vehicleTypes()->attach($request->vehicle_types);

        return redirect()->route(Auth::user()->role->name . '.sections.index')
            ->with('success', 'Sección '.$section->name.' creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        $section->load('vehicleTypes', 'questions');
        return view('modules.section.show', compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $vehicleTypes = VehicleType::all();
        $selectedVehicleTypes = $section->vehicleTypes->pluck('id')->toArray();
        
        return view('modules.section.edit', compact('section', 'vehicleTypes', 'selectedVehicleTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:sections,name,' . $section->id,
            'vehicle_types' => 'required|array',
            'vehicle_types.*' => 'exists:vehicle_types,id',
        ], [
            'name.required' => 'El nombre de la sección es obligatorio.',
            'name.unique' => 'Ya existe una sección con este nombre.',
            'vehicle_types.required' => 'Debe seleccionar al menos un tipo de vehículo.',
            'vehicle_types.*.exists' => 'Uno de los tipos de vehículo seleccionados no existe.',
        ]);

        $section->update([
            'name' => $request->name,
        ]);

        // Sincronizar los tipos de vehículo (elimina los que no están en el array y añade los nuevos)
        $section->vehicleTypes()->sync($request->vehicle_types);

        return redirect()->route(Auth::user()->role->name . '.sections.index')
            ->with('success', 'Sección '.$section->name.' actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        // Verificar si hay preguntas asociadas a esta sección
        if ($section->questions()->count() > 0) {
            return redirect()->route(Auth::user()->role->name . '.sections.index')
                ->with('error', 'No se puede eliminar esta sección porque tiene '.$section->questions.' preguntas asociadas.');
        }

        // Eliminar las relaciones con tipos de vehículo
        $section->vehicleTypes()->detach();
        
        // Eliminar la sección
        $section->delete();

        return redirect()->route(Auth::user()->role->name . '.sections.index')
            ->with('success', 'Sección eliminada exitosamente.');
    }
}
