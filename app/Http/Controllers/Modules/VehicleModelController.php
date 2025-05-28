<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VehicleModel::with('brand');

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'name';

        // Filtro por nombre
        if ($filterType == 'name' && $request->filled('name_search')) {
            $buscado = strtolower(trim($request->name_search));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por marca
        if ($filterType == 'brand' && $request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Paginamos y mantenemos los filtros en la URL
        $vehicleModels = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search', 'brand_id']));

        $brands = Brand::orderBy('name')->get();

        return view('modules.vehicle_model.index', compact('vehicleModels', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        return view('modules.vehicle_model.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar unicidad de nombre dentro de la misma marca
        $exists = VehicleModel::where('name', $request->name)
            ->where('brand_id', $request->brand_id)
            ->exists();

        if ($exists) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.create')
                ->withErrors(['name' => 'Ya existe un modelo con este nombre para la marca seleccionada.'])
                ->withInput();
        }

        VehicleModel::create($request->all());

        return redirect()->route(Auth::user()->role->name .'.vehicle-models.index')
            ->with('success', 'Modelo de vehículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Carga la marca y el conteo de vehículos si lo necesitas
        $model = VehicleModel::with('brand', 'vehicles')->findOrFail($id);
        return view('modules.vehicle_model.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $model = VehicleModel::findOrFail($id);
        // Necesitamos la lista de marcas para el <select>
        $brands = Brand::orderBy('name')->get();
        return view('modules.vehicle_model.edit', compact('model', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleModel $vehicleModel)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.edit', $vehicleModel)
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar unicidad de nombre dentro de la misma marca, excluyendo el modelo actual
        $exists = VehicleModel::where('name', $request->name)
            ->where('brand_id', $request->brand_id)
            ->where('id', '!=', $vehicleModel->id)
            ->exists();

        if ($exists) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.edit', $vehicleModel)
                ->withErrors(['name' => 'Ya existe un modelo con este nombre para la marca seleccionada.'])
                ->withInput();
        }

        $vehicleModel->update($request->all());

        return redirect()->route(Auth::user()->role->name .'.vehicle-models.index')
            ->with('success', 'Modelo de vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleModel $vehicleModel)
    {
        // Verificar si hay vehículos asociados a este modelo
        if ($vehicleModel->vehicles()->count() > 0) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.index')
                ->with('error', 'No se puede eliminar este modelo porque tiene vehículos asociados.');
        }

        try {
            $vehicleModel->delete();
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.index')
                ->with('success', 'Modelo de vehículo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-models.index')
                ->with('error', 'Error al eliminar el modelo de vehículo.');
        }
    }
}
