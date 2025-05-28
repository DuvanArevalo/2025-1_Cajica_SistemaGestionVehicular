<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VehicleType::query();

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'name';

        // Filtro por nombre
        if ($filterType == 'name' && $request->filled('name_search')) {
            $buscado = strtolower(trim($request->name_search));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por descripción
        if ($filterType == 'description' && $request->filled('description_search')) {
            $buscado = strtolower(trim($request->description_search));
            $query->whereRaw('LOWER(description) LIKE ?', ["%{$buscado}%"]);
        }

        // Paginamos y mantenemos los filtros en la URL
        $vehicleTypes = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search', 'description_search']));

        return view('modules.vehicle_type.index', compact('vehicleTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.vehicle_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vehicle_types',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name . '.vehicle-types.create')
                ->withErrors($validator)
                ->withInput();
        }

        VehicleType::create($request->all());

        return redirect()->route(Auth::user()->role->name . '.vehicle-types.index')
            ->with('success', 'Tipo de vehículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicleType)
    {
        return view('modules.vehicle_type.show', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleType $vehicleType)
    {
        return view('modules.vehicle_type.edit', compact('vehicleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vehicle_types,name,' . $vehicleType->id,
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name . '.vehicle-types.edit', $vehicleType)
                ->withErrors($validator)
                ->withInput();
        }

        $vehicleType->update($request->all());

        return redirect()->route(Auth::user()->role->name . '.vehicle-types.index')
            ->with('success', 'Tipo de vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        // Verificar si hay vehículos asociados a este tipo
        if ($vehicleType->vehicles()->count() > 0) {
            return redirect()->route(Auth::user()->role->name . '.vehicle-types.index')
                ->with('error', 'No se puede eliminar este tipo de vehículo porque tiene vehículos asociados.');
        }

        try {
            $vehicleType->delete();
            return redirect()->route(Auth::user()->role->name . '.vehicle-types.index')
                ->with('success', 'Tipo de vehículo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name . '.vehicle-types.index')
                ->with('error', 'Error al eliminar el tipo de vehículo.');
        }
    }
}
