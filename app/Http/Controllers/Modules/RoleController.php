<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Asegúrate de importar el Validator

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query();

        // Filtrar según el tipo de búsqueda seleccionado
        $filterType = $request->filter_type ?? 'name'; // Por defecto, filtrar por 'name'

        // --- Filtros específicos ---

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

        // Filtro por estado de actividad (is_active)
        if ($filterType == 'is_active' && $request->filled('active_status')) {
            // '1' para activo, '0' para inactivo, 'all' para todos
            if ($request->active_status !== 'all') {
                $query->where('is_active', (bool) $request->active_status);
            }
        }

        // Filtro por rango de fechas de creación (created_at)
        if ($filterType == 'date_range') {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        // --- Ordenamiento y Paginación ---
        $roles = $query
            ->orderBy('created_at', 'desc') // Ordena por fecha de creación descendente por defecto
            ->paginate(10) // Cambié la paginación a 10 por ser más usual
            ->appends($request->only(['filter_type', 'name_search', 'description_search', 'active_status', 'date_from', 'date_to'])); // Mantiene los filtros en la URL

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
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('roles.create') // Ajusta esta ruta si es diferente
                ->withErrors($validator)
                ->withInput();
        }

        Role::create($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado correctamente.');
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
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('roles.edit', $role->id) // Ajusta esta ruta si es diferente
                ->withErrors($validator)
                ->withInput();
        }

        $role->update($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Rol: ' . $role->name . ' actualizado correctamente.');
    }



    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Role $role)
    {
        $role->is_active = !$role->is_active;
        $role->save();

        return back()->with('success', 'Estado del rol actualizado.');
    }
}