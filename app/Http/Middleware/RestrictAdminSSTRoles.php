<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

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
                    }
                }
            }
        }
        
        return $next($request);
    }
}
