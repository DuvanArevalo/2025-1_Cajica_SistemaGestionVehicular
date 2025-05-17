<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Alert;
use App\Models\PreoperationalForm;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Obtener fechas de filtro
        $mes = $request->mes;
        $desde = $request->desde;
        $hasta = $request->hasta;
        
        // Consulta base para usuarios
        $usuariosQuery = User::query();
        $vehiculosQuery = Vehicle::query();
        $alertasQuery = Alert::query();
        $formulariosQuery = PreoperationalForm::query();
        
        // Aplicar filtros si existen
        if ($mes) {
            $year = date('Y');
            $startDate = Carbon::createFromDate($year, $mes, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $mes, 1)->endOfMonth();
            
            $usuariosQuery->whereBetween('created_at', [$startDate, $endDate]);
            $vehiculosQuery->whereBetween('created_at', [$startDate, $endDate]);
            $alertasQuery->whereBetween('created_at', [$startDate, $endDate]);
            $formulariosQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($desde && $hasta) {
            $usuariosQuery->whereBetween('created_at', [$desde, $hasta]);
            $vehiculosQuery->whereBetween('created_at', [$desde, $hasta]);
            $alertasQuery->whereBetween('created_at', [$desde, $hasta]);
            $formulariosQuery->whereBetween('created_at', [$desde, $hasta]);
        }
        
        // Contar totales
        $cantidadUsuarios = User::count();
        $cantidadVehiculos = Vehicle::count();
        $cantidadAlertas = Alert::count();
        $cantidadFormularios = PreoperationalForm::count();
        
        // Contar filtrados
        $usuariosEsteMes = $usuariosQuery->count();
        $vehiculosEsteMes = $vehiculosQuery->count();
        $alertasEsteMes = $alertasQuery->count();
        $formulariosEsteMes = $formulariosQuery->count();
        
        // Datos para la gráfica (valores únicos)
        $datosGrafica = [
            'usuarios' => $this->obtenerDatosHistoricos(User::class),
            'vehiculos' => $this->obtenerDatosHistoricos(Vehicle::class),
            'alertas' => $this->obtenerDatosHistoricos(Alert::class),
            'formularios' => $this->obtenerDatosHistoricos(PreoperationalForm::class)
        ];
        
        // Agregar años disponibles (por ejemplo, últimos 5 años)
        $añoActual = date('Y');
        $añosDisponibles = range($añoActual - 4, $añoActual);
        
        return view('dashboard.admin', [
            'cantidadUsuarios' => $cantidadUsuarios,
            'usuariosEsteMes' => $usuariosEsteMes,
            'cantidadVehiculos' => $cantidadVehiculos,
            'vehiculosEsteMes' => $vehiculosEsteMes,
            'cantidadAlertas' => $cantidadAlertas,
            'alertasEsteMes' => $alertasEsteMes,
            'cantidadFormularios' => $cantidadFormularios,
            'formulariosEsteMes' => $formulariosEsteMes,
            'desde' => $desde,
            'hasta' => $hasta,
            'datosGrafica' => $datosGrafica,
            'añosDisponibles' => $añosDisponibles,
            'año' => $request->input('año', date('Y')), // Año actual por defecto
        ]);
    }

    /**
     * Obtiene datos históricos para la gráfica
     */
    private function obtenerDatosHistoricos($modelo)
    {
        $datosMensuales = [];
        $mesActual = Carbon::now()->month; // Mes actual (1-12)
        $añoActual = Carbon::now()->year;
    
        for ($mes = 1; $mes <= 12; $mes++) {
            $inicioMes = Carbon::create($añoActual, $mes, 1)->startOfMonth();
            $finMes = Carbon::create($añoActual, $mes, 1)->endOfMonth();
    
            $conteo = $modelo::whereBetween('created_at', [$inicioMes, $finMes])->count();
            $datosMensuales[] = $conteo;
        }
    
        return $datosMensuales;
    }
}
