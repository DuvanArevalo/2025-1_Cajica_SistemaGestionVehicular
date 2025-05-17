<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Alert;
use App\Models\PreoperationalForm;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $mes = $request->input('mes');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
    
        if ($mes) {
            // Prioridad al mes seleccionado
            $desdeFecha = Carbon::createFromDate(date('Y'), (int)$mes, 1)->startOfMonth();
            $hastaFecha = Carbon::createFromDate(date('Y'), (int)$mes, 1)->endOfMonth();
            // Opcional: puedes limpiar los valores de $desde y $hasta para que el formulario también los limpie visualmente
            $desde = null;
            $hasta = null;
        } else {
            // Si no hay mes, usa fechas personalizadas o el mes actual por defecto
            $desdeFecha = $desde ? Carbon::parse($desde)->startOfDay() : Carbon::now()->startOfMonth();
            $hastaFecha = $hasta ? Carbon::parse($hasta)->endOfDay() : Carbon::now()->endOfMonth();
        }
    
        // Conteos filtrados por fecha
        $cantidadUsuarios = User::whereBetween('created_at', [$desdeFecha, $hastaFecha])->count();
        $cantidadVehiculos = Vehicle::whereBetween('created_at', [$desdeFecha, $hastaFecha])->count();
        $cantidadAlertas = Alert::whereBetween('created_at', [$desdeFecha, $hastaFecha])->count();
        $cantidadFormularios = PreoperationalForm::whereBetween('created_at', [$desdeFecha, $hastaFecha])->count();
    
        // Ahora los "este mes" también usan el filtro aplicado
        $usuariosEsteMes = $cantidadUsuarios;
        $vehiculosEsteMes = $cantidadVehiculos;
        $alertasEsteMes = $cantidadAlertas;
        $formulariosEsteMes = $cantidadFormularios;
    
        return view('dashboard.admin', compact(
            'cantidadUsuarios',
            'usuariosEsteMes',
            'cantidadVehiculos',
            'vehiculosEsteMes',
            'cantidadAlertas',
            'alertasEsteMes',
            'cantidadFormularios',
            'formulariosEsteMes',
            'desde',
            'hasta'
        ));
    }
}
