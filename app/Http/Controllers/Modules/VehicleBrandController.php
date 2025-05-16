<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use Illuminate\Http\Request;

class VehicleBrandController extends Controller
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

        $brands = Brand::all();
        return view('brands.index', compact('brands', 'dashboardRoute'));
    }



        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('brands.create');

        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Brand::create($request->all());

        return redirect()->route('brands.index')->with('success', 'Marca registrada correctamente.');
    }



        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update($request->all());

        return redirect()->route('brands.index')->with('success', 'Marca actualizada correctamente.');

    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Marca eliminada correctamente.');

    public function destroy(Brand $brand)
    {
        //

    }
}
