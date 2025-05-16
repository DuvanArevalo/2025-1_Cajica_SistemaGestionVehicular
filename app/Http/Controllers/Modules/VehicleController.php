<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // Mostrar catálogo
    public function index(Request $request)
    {
        $query = Vehicle::with(['brand', 'model', 'vehicleType']);

    // Aplicar filtros según los parámetros recibidos
        if ($request->filled('marca')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->marca . '%');
            });
        }

        if ($request->filled('modelo')) {
            $query->whereHas('model', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->modelo . '%');
            });
        }

        if ($request->filled('placa')) {
            $query->where('plate', 'like', '%' . $request->placa . '%');
        }

        if ($request->filled('tipo')) {
            $query->whereHas('vehicleType', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->tipo . '%');
            });
        }

        if ($request->filled('año')) {
            $query->where('model_year', $request->año);
        }

        if ($request->filled('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }

    // Obtener resultados filtrados
        $vehicles = $query->get();

        return view('catalogo.index', compact('vehicles'));
    }


    // Mostrar formulario de registro
    public function create()
{
    $vehicleTypes = VehicleType::all();
    $brands = Brand::all();
    $vehicleModels = VehicleModel::all();

    return view('vehiculos.create', compact('vehicleTypes', 'brands', 'vehicleModels'));
}

    // Guardar vehículo
    public function store(Request $request)
    {
    $validated = $request->validate([
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'vehicle_type_id' => 'required|integer',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'brand_id' => 'required|integer',
        'model_id' => 'required|integer',
        'model_year' => 'required|max:4',
        'wheel_count' => 'required|max:2',
        'color' => 'required|max:50',
        'plate' => 'required|max:6|unique:vehicles,plate',
        'mileage' => 'required|numeric',
        'is_active' => 'required|boolean',
        'soat' => 'required|date',
        'soat_status' => 'required|boolean',
        'mechanical_review' => 'required|date',
        'mechanical_review_status' => 'required|boolean',
    ],[
        'plate.unique' => 'La placa ya está registrada en el sistema.',
    ]);

    // Si se subió una imagen, guardarla
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images'), $filename);
        $validated['image'] = $filename;
    
        // Opcional: puedes mostrar el nombre o guardar temporalmente en sesión
        session(['uploaded_image' => $filename]);
    }

    Vehicle::create($validated);

    return redirect()->route('catalogo.index')->with('success', 'Vehículo registrado correctamente.');
    }

    public function show($id) {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehiculos.show', compact('vehicle'));
    }

    public function edit($id) {
        $vehicleTypes = VehicleType::all();
        $brands = Brand::all();
        $vehicleModels = VehicleModel::all();
        $vehicle = Vehicle::findOrFail($id);

        return view('vehiculos.edit', compact('vehicle', 'vehicleTypes', 'brands', 'vehicleModels'));
    }

    public function update(Request $request, $id) {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());
        return redirect()->route('catalogo.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Verifica si el usuario tiene permisos para eliminar
        if (!Auth::check() || !in_array(Auth::user()->role->name, ['admin', 'sst'])) {
            return redirect()->route('catalogo.index')->with('error', 'No tienes permisos para eliminar vehículos.');
        }

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('catalogo.index')->with('success', 'Vehículo eliminado correctamente.');
    }


}
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
