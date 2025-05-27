<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'name';

        // Filtro por nombre
        if ($filterType == 'name' && $request->filled('name_search')) {
            $buscado = strtolower(trim($request->name_search));
            $query->whereRaw('LOWER(name1) LIKE ? OR LOWER(lastname1) LIKE ?', ["%{$buscado}%", "%{$buscado}%"]);
        }

        // Filtro por documento
        if ($filterType == 'document' && $request->filled('document_search')) {
            $buscado = trim($request->document_search);
            $query->where('document_number', 'LIKE', "%{$buscado}%");
        }

        // Filtro por email
        if ($filterType == 'email' && $request->filled('email_search')) {
            $buscado = strtolower(trim($request->email_search));
            $query->whereRaw('LOWER(email) LIKE ?', ["%{$buscado}%"]);
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
        $users = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['filter_type', 'name_search', 'document_search', 'email_search', 'date_from', 'date_to']));

        return view('modules.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        $documentTypes = DocumentType::all();
        
        // Si el usuario es SST, filtrar los roles disponibles
        if (Auth::user()->role->name === 'sst') {
            $roles = $roles->filter(function($role) {
                return in_array(strtolower($role->name), ['sst', 'conductor']);
            });
        }
        
        return view('modules.user.create', compact('roles', 'documentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name1' => 'required|string|max:255',
            'name2' => 'nullable|string|max:255',
            'lastname1' => 'required|string|max:255',
            'lastname2' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'document_type_id' => 'required|exists:document_types,id',
            'document_number' => 'required|string|max:20|unique:users',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name . '.users.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Preparar datos para crear el usuario
        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);
        $userData['is_active'] = $request->has('is_active');

        User::create($userData);

        return redirect()->route(Auth::user()->role->name . '.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('modules.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $documentTypes = DocumentType::all();
        
        // Si el usuario es SST, filtrar los roles disponibles
        if (Auth::user()->role->name === 'sst') {
            $roles = $roles->filter(function($role) use ($user) {
                // Si el usuario ya tiene rol SST, permitir mantenerlo
                if ($user->role->name === 'sst' && $role->name === 'sst') {
                    return true;
                }
                return in_array(strtolower($role->name), ['sst', 'conductor']);
            });
        }
        
        return view('modules.user.edit', compact('user', 'roles', 'documentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name1' => 'required|string|max:255',
            'name2' => 'nullable|string|max:255',
            'lastname1' => 'required|string|max:255',
            'lastname2' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'document_type_id' => 'required|exists:document_types,id',
            'document_number' => 'required|string|max:20|unique:users,document_number,' . $user->id,
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name . '.users.edit', $user->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Preparar datos para actualizar el usuario
        $userData = $request->except(['password', 'password_confirmation']);
        
        // Solo actualizar la contraseña si se proporciona una nueva
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        // Manejar el estado activo/inactivo
        $userData['is_active'] = $request->has('is_active');

        $user->update($userData);

        return redirect()->route(Auth::user()->role->name . '.users.index')
            ->with('success', 'Usuario: ' . $user->name1 . ' ' . $user->lastname1 . ' actualizado exitosamente.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Verificar si el usuario a modificar tiene rol admin
        if ($user->role->name === 'admin') {
            return redirect()->route(Auth::user()->role->name . '.users.index')
                ->with('error', 'No se puede cambiar el estado de un usuario con rol administrador.');
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activado' : 'desactivado';
        
        return redirect()->route(Auth::user()->role->name . '.users.index')
            ->with('success', 'Usuario ' . $status . ' exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route(Auth::user()->role->name . '.users.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route(Auth::user()->role->name . '.users.index')
                ->with('error', 'No se puede eliminar este usuario porque está siendo utilizado en el sistema.');
        }
    }
}