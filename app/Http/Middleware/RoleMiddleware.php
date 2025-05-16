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
            return redirect()->route('login')->with('error', 'Tu cuenta no existe o no se ha verificado. Contacta al administrador o Seguridad y Salud en el Trabajo.');
        }

        // Verificar si el usuario está activo
        if (!Auth::user()->is_active) {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
            return redirect()->route('login')->with('error', 'Tu cuenta está desactivada. Contacta al administrador o Seguridad y Salud en el Trabajo.');
        }

        // 2. Obtener el rol del usuario autenticado
        // Usamos la relación 'role' definida en el modelo User y accedemos al atributo 'name' del rol.
        $userRole = strtolower(Auth::user()->role->name); // Convertir a minúsculas para comparación insensible

        // 3. Verificar si el rol del usuario está en la lista de roles permitidos
        $allowedRoles = array_map('strtolower', $roles); // Convertir roles permitidos a minúsculas


        if (!in_array($userRole, $allowedRoles)) {
            // Si el rol no está permitido, redirigir a una página de no autorizado o al home
            abort(403, 'Acceso no autorizado.');

        if (!in_array($userRole, $allowedRoles)) {
            $currentUrl = $request->fullUrl();
            $baseMessage = 'No tienes permisos para acceder a la ruta: ' . $currentUrl;
            
            return match ($userRole) {
                'admin'     => redirect()->route('admin.dashboard')->with('error', '¡Lo sentimos! ' . $baseMessage . '. Habla con el administrador o con el equipo de SST.'),
                'sst'       => redirect()->route('sst.dashboard')->with('error', '¡Lo sentimos! ' . $baseMessage),
                'conductor' => redirect()->route('conductor.dashboard')->with('error', '¡Lo sentimos! ' . $baseMessage),                default     => redirect()->route('login'),
            };
        }

        // 4. Si el rol es permitido, continuar con la solicitud
        return $next($request);
    }
}