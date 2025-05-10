<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // Mostrar catálogo
    public function index()
    {
    $vehicles = Vehicle::with(['brand', 'model', 'type'])->get();
    return view('catalogo.index', compact('vehicles'));
    }

    // Mostrar formulario de registro
    public function create()
    {
        return view('vehiculos.create');
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
}