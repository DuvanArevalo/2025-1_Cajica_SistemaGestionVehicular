<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Alert;
use App\Models\PreoperationalForm;
use Carbon\Carbon;
use App\Models\VehicleModel;

class SSTController extends Controller
{
    public function index(Request $request)
    {
        // Obtener fechas de filtro
        $mes = $request->mes;
        $desde = $request->desde;
        $hasta = $request->hasta;
        
        // Obtener el año seleccionado o usar el año actual por defecto
        $añoSeleccionado = $request->input('año', Carbon::now()->year);
        
        // Consulta base para usuarios
        $usuariosQuery = User::query();
        $vehiculosQuery = Vehicle::query();
        $alertasQuery = Alert::query();
        $formulariosQuery = PreoperationalForm::query();
        
        // Aplicar filtros si existen
        if ($mes) {
            $startDate = Carbon::createFromDate($añoSeleccionado, $mes, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($añoSeleccionado, $mes, 1)->endOfMonth();
            
            $usuariosQuery->whereBetween('created_at', [$startDate, $endDate]);
            $vehiculosQuery->whereBetween('created_at', [$startDate, $endDate]);
            $alertasQuery->whereBetween('created_at', [$startDate, $endDate]);
            $formulariosQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($desde && $hasta) {
            $usuariosQuery->whereBetween('created_at', [$desde, $hasta]);
            $vehiculosQuery->whereBetween('created_at', [$desde, $hasta]);
            $alertasQuery->whereBetween('created_at', [$desde, $hasta]);
            $formulariosQuery->whereBetween('created_at', [$desde, $hasta]);
        } else {
            // Si no hay filtros, filtrar por el año seleccionado
            $startOfYear = Carbon::createFromDate($añoSeleccionado, 1, 1)->startOfYear();
            $endOfYear = Carbon::createFromDate($añoSeleccionado, 12, 31)->endOfYear();
            
            $usuariosQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
            $vehiculosQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
            $alertasQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
            $formulariosQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
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
        
        // Datos para la gráfica (pasando el año seleccionado)
        $datosGrafica = [
            'usuarios' => $this->obtenerDatosHistoricos(User::class, $añoSeleccionado),
            'vehiculos' => $this->obtenerDatosHistoricos(Vehicle::class, $añoSeleccionado),
            'alertas' => $this->obtenerDatosHistoricos(Alert::class, $añoSeleccionado),
            'formularios' => $this->obtenerDatosHistoricos(PreoperationalForm::class, $añoSeleccionado)
        ];
        
        // Agregar años disponibles (5 años atrás y 2 adelante para flexibilidad)
        $añoActual = date('Y');
        $añosDisponibles = range($añoActual - 5, $añoActual + 2);
        
        return view('dashboard.sst', [
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
            'año' => $añoSeleccionado,
        ]);
    }

    /**
     * Obtiene datos históricos para la gráfica
     * @param string $modelo El modelo a consultar
     * @param int|null $año El año a filtrar (null para año actual)
     */
    private function obtenerDatosHistoricos($modelo, $año = null)
    {
        $datosMensuales = [];
        $año = $año ?? Carbon::now()->year;
    
        for ($mes = 1; $mes <= 12; $mes++) {
            $inicioMes = Carbon::create($año, $mes, 1)->startOfMonth();
            $finMes = Carbon::create($año, $mes, 1)->endOfMonth();
    
            // Consulta optimizada usando whereYear y whereMonth
            $conteo = $modelo::whereYear('created_at', $año)
                            ->whereMonth('created_at', $mes)
                            ->count();
            
            $datosMensuales[] = $conteo;
        }
        
        return $datosMensuales;
    }
}