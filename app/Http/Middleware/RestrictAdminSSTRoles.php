<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class RestrictAdminSSTRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        // Si es una solicitud para editar o actualizar un usuario o rol
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            
            // Para controlador de usuarios
            if ($request->route()->getName() == 'sst.users.update' || $request->route()->getName() == 'sst.users.store') {
                $roleId = $request->input('role_id');
                
                // Verificar si el rol es admin o sst
                $restrictedRoles = Role::whereIn('name', ['admin', 'sst'])->pluck('id')->toArray();
                
                if (in_array($roleId, $restrictedRoles)) {
                    return redirect()->back()->with('error', 'No tienes permisos para asignar roles de Administrador o SST.');
                }
            }
            
            // Para controlador de roles
            if ($request->route()->getName() == 'sst.roles.update' || $request->route()->getName() == 'sst.roles.store') {
                $roleId = $request->route('role');
                
                if ($roleId) {
                    $role = Role::find($roleId);
                    if ($role && in_array($role->name, ['admin', 'sst'])) {
                        return redirect()->back()->with('error', 'No tienes permisos para modificar roles de Administrador o SST.');
        // Obtener el usuario autenticado y su rol
        $user = Auth::user();
        
        // Verificar que el usuario esté autenticado
        if (!$user) {
            return redirect()->route('login');
        }
        
        $userRole = strtolower($user->role->name);
        
        // Si el usuario es admin, permitir todas las operaciones
        if ($userRole === 'admin') {
            return $next($request);
        }
        
        // Si es una solicitud para editar o actualizar un usuario o rol
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            
            // Si el usuario tiene rol SST
            if ($userRole === 'sst') {
                
                // Para controlador de usuarios (crear o actualizar)
                if ($request->route()->getName() == 'sst.users.update' || $request->route()->getName() == 'sst.users.store') {
                    $roleId = $request->input('role_id');
                    
                    // Obtener los IDs de los roles
                    $adminRoleId = Role::where('name', 'admin')->first()->id;
                    $sstRoleId = Role::where('name', 'sst')->first()->id;
                    $conductorRoleId = Role::where('name', 'conductor')->first()->id;
                    
                    // SST no puede asignar rol admin
                    if ($roleId == $adminRoleId) {
                        return redirect()->back()->with('error', 'No tienes permisos para asignar rol de Administrador.');
                    }
                    
                    // Si es una actualización, verificar si está cambiando de SST a conductor
                    if ($request->route()->getName() == 'sst.users.update') {
                        $userId = $request->route('user');
                        $userToUpdate = User::find($userId);
                        
                        if ($userToUpdate && $userToUpdate->role->name == 'sst' && $roleId == $conductorRoleId) {
                            return redirect()->back()->with('error', 'No puedes cambiar un usuario de SST a Conductor.');
                        }
                    }
                }
                
                // Para controlador de roles
                if ($request->route()->getName() == 'sst.roles.update' || $request->route()->getName() == 'sst.roles.store') {
                    $roleId = $request->route('role');
                    
                    if ($roleId) {
                        $role = Role::find($roleId);
                        if ($role && $role->name == 'admin') {
                            return redirect()->back()->with('error', 'No tienes permisos para modificar el rol de Administrador.');
                        }
                    }
                }
            } else {
                // Si no es admin ni SST, no puede asignar roles privilegiados
                if ($request->route()->getName() == 'sst.users.update' || $request->route()->getName() == 'sst.users.store') {
                    $roleId = $request->input('role_id');
                    
                    // Verificar si el rol es admin o sst
                    $restrictedRoles = Role::whereIn('name', ['admin', 'sst'])->pluck('id')->toArray();
                    
                    if (in_array($roleId, $restrictedRoles)) {
                        return redirect()->back()->with('error', 'No tienes permisos para asignar roles de Administrador o SST.');
                    }
                }
                
                // Para controlador de roles
                if ($request->route()->getName() == 'sst.roles.update' || $request->route()->getName() == 'sst.roles.store') {
                    $roleId = $request->route('role');
                    
                    if ($roleId) {
                        $role = Role::find($roleId);
                        if ($role && in_array($role->name, ['admin', 'sst'])) {
                            return redirect()->back()->with('error', 'No tienes permisos para modificar roles de Administrador o SST.');
                        }
                    }
                }
            }
        }
        
        return $next($request);
    }
}
