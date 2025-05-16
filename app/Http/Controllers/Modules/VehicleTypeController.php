<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $role = Auth::check() ? strtolower(Auth::user()->role->name) : null;

        $dashboardRoute = match($role) {
            'admin' => route('admin.dashboard'),
            'sst' => route('sst.dashboard'),
            'conductor' => route('conductor.dashboard'),
            default => route('home')
        };

        $vehicleTypes = VehicleType::all();
        return view('vehicle-types.index', compact('vehicleTypes', 'dashboardRoute'));
    }


        //
    }

    public function create()
    {

        return view('vehicle-types.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        VehicleType::create($request->all());
        return redirect()->route('vehicle-types.index')->with('success', 'Tipo de vehículo registrado correctamente.');
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicleType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('vehicle-types.edit', compact('vehicleType'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->update($request->all());

        return redirect()->route('vehicle-types.index')->with('success', 'Tipo de vehículo actualizado correctamente.');
    public function edit(VehicleType $vehicleType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role->name, ['admin', 'sst'])) {
            return redirect()->route('vehicle-types.index')->with('error', 'No tienes permisos para eliminar tipos de vehículos.');
        }

        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();

        return redirect()->route('vehicle-types.index')->with('success', 'Tipo de vehículo eliminado correctamente.');
    }

    public function destroy(VehicleType $vehicleType)
    {
        //
    }
}
