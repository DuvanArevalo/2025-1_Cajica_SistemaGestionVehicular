<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query();

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
        $roles = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search', 'description_search', 'date_from', 'date_to']));

        return view('modules.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.roles.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Preparar datos para crear
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Role::create($data);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('modules.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('modules.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.roles.edit', $role->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Preparar datos para actualizar
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $role->update($data);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol: '.$role->name.' actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Verificar si hay usuarios asociados a este rol
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se puede eliminar este Rol porque hay usuarios asociados.');
        }

        try {
            $role->delete();
            return redirect()->route('admin.roles.index')
                ->with('success', 'Rol eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se puede eliminar este Rol porque está siendo utilizado en el sistema.');
        }
    }
}
