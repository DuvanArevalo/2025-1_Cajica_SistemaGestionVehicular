<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Los roles permitidos para la ruta.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Si no está autenticado, redirigir al login
            return redirect()->route('login');
        }

        // Verificar si el usuario está activo
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
        }

        // 2. Obtener el rol del usuario autenticado
        // Usamos la relación 'role' definida en el modelo User y accedemos al atributo 'name' del rol.
        $userRole = strtolower(Auth::user()->role->name); // Convertir a minúsculas para comparación insensible

        // 3. Verificar si el rol del usuario está en la lista de roles permitidos
        $allowedRoles = array_map('strtolower', $roles); // Convertir roles permitidos a minúsculas

        if (!in_array($userRole, $allowedRoles)) {
            // Si el rol no está permitido, redirigir a una página de no autorizado o al home
            abort(403, 'Acceso no autorizado.');
        }

        // 4. Si el rol es permitido, continuar con la solicitud
        return $next($request);
    }
}