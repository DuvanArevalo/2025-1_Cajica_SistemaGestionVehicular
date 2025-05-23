<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar usuarios
 
    // Mostrar un usuario específico
    public function index(Request $request)
    {
        $query = User::with('role');
    
        switch ($request->filter_type) {
            case 'name':
                if ($request->filled('name_search')) {
                    $name = trim($request->name_search);
                    $query->whereRaw(
                        "CONCAT_WS(' ', name1, name2, lastname1, lastname2) LIKE ?",
                        ["%{$name}%"]
                    );
                }
                break;
    
            case 'email':
                if ($request->filled('email_search')) {
                    $query->where('email', 'like', '%'.$request->email_search.'%');
                }
                break;
    
            case 'role':
                if ($request->filled('role_search')) {
                    $query->whereHas('role', fn ($q) =>
                        $q->where('name', 'like', '%'.$request->role_search.'%')
                    );
                }
                break;
                case 'document':
                    if ($request->filled('document_search')) {
                        $query->where('document_number', 'like', '%'.$request->document_search.'%');
                    }
                    break;
        }
    
        $users = $query->orderByDesc('id')
                       ->paginate(1)
                       ->withQueryString();
    
        return view('modules.user.index', compact('users'));
    }
    

    // Mostrar formulario para crear un nuevo usuario
    public function create()
    {
        $roles = match (auth()->user()->role->name) {
            'admin' => Role::all(),
            'sst'   => Role::whereIn('name', ['sst', 'conductor'])->get(),
            default => collect(),
        };        $documentTypes = DocumentType::all();
        return view('modules.user.create', compact('roles', 'documentTypes'));
    }

    // Guardar un nuevo usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name1' => 'required|max:255',
            'name2' => 'nullable|max:255',
            'lastname1' => 'required|max:255',
            'lastname2' => 'nullable|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'document_type_id' => 'required|exists:document_types,id',
            'document_number' => 'required|unique:users,document_number',
            'is_active' => 'required|boolean',
        ]);

        User::create([
            'name1' => $validated['name1'],
            'name2' => $validated['name2'],
            'lastname1' => $validated['lastname1'],
            'lastname2' => $validated['lastname2'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
            'document_type_id' => $validated['document_type_id'],
            'document_number' => $validated['document_number'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario para editar un usuario
    public function edit(User $user)
    {
        $roles = Role::all();
        $documentTypes = DocumentType::all();
        return view('modules.user.edit', compact('user', 'roles', 'documentTypes'));
    }

    // Actualizar un usuario existente
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name1' => 'required|max:255',
            'name2' => 'nullable|max:255',
            'lastname1' => 'required|max:255',
            'lastname2' => 'nullable|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'document_type_id' => 'required|exists:document_types,id',
            'document_number' => 'required|unique:users,document_number,' . $user->id,
            'is_active' => 'required|boolean',
        ]);
    
        // Validar roles permitidos según el tipo de usuario autenticado
        $allowedRoles = match (auth()->user()->role->name) {
            'admin' => Role::pluck('id')->toArray(),
            'sst'   => Role::whereIn('name', ['sst', 'conductor'])->pluck('id')->toArray(),
            default => [],
        };
    
        if (!in_array($validated['role_id'], $allowedRoles)) {
            return redirect()->back()->withErrors(['role_id' => 'No tienes permiso para asignar este rol.']);
        }
    
        $user->update($validated);
    
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }
    

    // Eliminar un usuario
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    // Activar o desactivar un usuario
    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Estado del usuario actualizado.');
    }
    public function show(User $user)
    {
        return view('modules.user.show', compact('user'));
    }
}

    // Mostrar un usuario específico

