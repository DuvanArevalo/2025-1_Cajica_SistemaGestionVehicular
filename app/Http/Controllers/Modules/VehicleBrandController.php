<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brand::query();

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'name';

        // Filtro por nombre
        if ($filterType == 'name' && $request->filled('name_search')) {
            $buscado = strtolower(trim($request->name_search));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$buscado}%"]);
        }

        // Paginamos y mantenemos los filtros en la URL
        $brands = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search']));

        return view('modules.vehicle_brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.vehicle_brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-brands.create')
                ->withErrors($validator)
                ->withInput();
        }

        Brand::create($request->all());

        return redirect()->route(Auth::user()->role->name .'.vehicle-brands.index')
            ->with('success', 'Marca de vehículo creada exitosamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cargamos relaciones para los conteos
        $brand = Brand::with(['vehicles', 'vehicleModels'])->findOrFail($id);
        return view('modules.vehicle_brand.show', compact('brand'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('modules.vehicle_brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route(Auth::user()->role->name .'.vehicle-brands.edit', $brand->id)
                ->withErrors($validator)
                ->withInput();
        }

        $brand->update($request->all());

        return redirect()->route(Auth::user()->role->name .'.vehicle-brands.index')
            ->with('success', 'Marca de vehículo actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->vehicles()->count() > 0 || $brand->vehicleModels()->count() > 0) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-brands.index')
                ->with('error', 'No se puede eliminar esta marca porque tiene vehículos o modelos asociados.');
        }

        try {
            $brand->delete();
            return redirect()->route(Auth::user()->role->name .'.vehicle-brands.index')
                ->with('success', 'Marca de vehículo eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name .'.vehicle-brands.index')
                ->with('error', 'Error al eliminar la marca de vehículo.');
        }
    }
}
