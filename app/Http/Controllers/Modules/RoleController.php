<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Listar roles
    public function index()
    {
        $roles = Role::paginate(1);
        return view('modules.role.index', compact('roles'));
    }

    // Mostrar un rol específico
    public function show(Role $role)
    {
        return view('modules.role.show', compact('role'));
    }

    // Mostrar formulario para crear un nuevo rol
    public function create()
    {
        return view('modules.role.create');
    }

    // Guardar un nuevo rol
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:roles,name',
            'description' => 'nullable|max:500',
            'is_active' => 'required|boolean',
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    // Mostrar formulario para editar un rol
    public function edit(Role $role)
    {
        return view('modules.role.edit', compact('role'));
    }

    // Actualizar un rol existente
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|max:500',
            'is_active' => 'required|boolean',
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    // Eliminar un rol
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    // Activar o desactivar un rol
    public function toggleActive(Role $role)
    {
        $role->is_active = !$role->is_active;
        $role->save();

        return back()->with('success', 'Estado del rol actualizado.');
    }
}