<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['vehicleType', 'brand', 'model']);

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'plate';

        // Filtro por placa
        if ($filterType == 'plate' && $request->filled('plate_search')) {
            $buscado = strtoupper(trim($request->plate_search));
            $query->where('plate', 'LIKE', "%{$buscado}%");
        }

        // Filtro por tipo de vehículo
        if ($filterType == 'type' && $request->filled('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        // Filtro por marca
        if ($filterType == 'brand' && $request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filtro por modelo
        if ($filterType == 'model' && $request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        // Filtro por estado activo/inactivo
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Paginamos y mantenemos los filtros en la URL
        $vehicles = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        // Cargar datos para los filtros
        $vehicleTypes = VehicleType::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $models = VehicleModel::orderBy('name')->get();

        return view('modules.vehicle.index', compact('vehicles', 'vehicleTypes', 'brands', 'models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $models = VehicleModel::orderBy('name')->get();

        return view('modules.vehicle.create', compact('vehicleTypes', 'brands', 'models'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:vehicles_models,id',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'wheel_count' => 'required|integer|min:1',
            'color' => 'required|string|max:50',
            'plate' => 'required|string|max:10|unique:vehicles',
            'mileage' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'soat' => 'required|date',
            'soat_status' => 'required|boolean',
            'mechanical_review' => 'required|date',
            'mechanical_review_status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.vehicles.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Preparar datos
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Vehicle::create($data);

        return redirect()->route(Auth::user()->role->name .'.vehicles.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['vehicleType', 'brand', 'model']);
        return view('modules.vehicle.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $models = VehicleModel::orderBy('name')->get();

        return view('modules.vehicle.edit', compact('vehicle', 'vehicleTypes', 'brands', 'models'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'required|exists:vehicles_models,id',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'wheel_count' => 'required|integer|min:1',
            'color' => 'required|string|max:50',
            'plate' => 'required|string|max:10|unique:vehicles,plate,' . $vehicle->id,
            'mileage' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'soat' => 'required|date',
            'soat_status' => 'required|boolean',
            'mechanical_review' => 'required|date',
            'mechanical_review_status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.vehicles.edit', $vehicle)
                ->withErrors($validator)
                ->withInput();
        }

        // Preparar datos
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $vehicle->update($data);

        return redirect()->route(Auth::user()->role->name .'.vehicles.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        // Verificar si hay formularios preoperacionales asociados
        if ($vehicle->preoperationalForms()->count() > 0) {
            return redirect()->route(Auth::user()->role->name .'.vehicles.index')
                ->with('error', 'No se puede eliminar este vehículo porque tiene formularios preoperacionales asociados.');
        }

        try {
            $vehicle->delete();
            return redirect()->route(Auth::user()->role->name .'.vehicles.index')
                ->with('success', 'Vehículo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name .'.vehicles.index')
                ->with('error', 'Error al eliminar el vehículo.');
        }
    }

    /**
     * Toggle the active status of the vehicle.
     */
    public function toggleStatus(Vehicle $vehicle)
    {
        $vehicle->is_active = !$vehicle->is_active;
        $vehicle->save();

        return redirect()->route(Auth::user()->role->name .'.vehicles.index')
            ->with('success', 'Estado del vehículo actualizado exitosamente.');
    }
}
