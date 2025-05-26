<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\VehicleModel;
use App\Models\Brand;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $vehicleModels = VehicleModel::with('brand')->get();

        // Definir la ruta del dashboard según el rol del usuario
        $role = Auth::check() ? strtolower(Auth::user()->role->name) : null;

        $dashboardRoute = match($role) {
            'admin' => route('admin.dashboard'),
            'sst' => route('sst.dashboard'),
            'conductor' => route('conductor.dashboard'),
            default => route('home')
        };

        return view('vehicle-models.index', compact('vehicleModels', 'dashboardRoute'));
    }


    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {

        $brands = Brand::all(); // Obtener todas las marcas para el formulario

        return view('vehicle-models.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id'
        ]);

        $vehicleModel = VehicleModel::create([
            'name' => $request->input('name'),
            'brand_id' => $request->input('brand_id')
        ]);

        if (!$vehicleModel) {
            return back()->withErrors(['msg' => 'Error al guardar el modelo.']);
        }

        return redirect()->route('vehicle-models.index')->with('success', 'Modelo registrado correctamente.');

        //

    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleModel $vehicleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $model = VehicleModel::findOrFail($id);
        $brands = Brand::all(); // Obtener todas las marcas disponibles
        return view('vehicle-models.edit', compact('model', 'brands'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id'
        ]);

        $model = VehicleModel::findOrFail($id);
        $model->update($request->all());

        return redirect()->route('vehicle-models.index')->with('success', 'Modelo actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $model = VehicleModel::findOrFail($id);
        $model->delete();

        return redirect()->route('vehicle-models.index')->with('success', 'Modelo eliminado correctamente.');
    }
}
