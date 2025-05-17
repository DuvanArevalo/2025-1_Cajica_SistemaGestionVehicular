<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Alert;
use App\Models\PreoperationalForm;
use Carbon\Carbon;
use \Illuminate\Contracts\View\View as View;

class SSTController extends Controller
{
    /**
     * Muestra el dashboard del SST.
     */
    public function index(Request $request): View
    {
        // Obtener fechas de filtro
        $mes = $request->mes;
        $desde = $request->desde;
        $hasta = $request->hasta;
        
        // Obtener el año seleccionado o usar el año actual por defecto
        $año = $request->año ?? Carbon::now()->year;
        
        // Consulta base para usuarios
        $usuariosQuery = User::query();
        $vehiculosQuery = Vehicle::query();
        $alertasQuery = Alert::query();
        $formulariosQuery = PreoperationalForm::query();
        
        // Aplicar filtros si existen
        if ($mes) {
            $startDate = Carbon::createFromDate($año, $mes, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($año, $mes, 1)->endOfMonth();
            
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
            $startOfYear = Carbon::createFromDate($año, 1, 1)->startOfYear();
            $endOfYear = Carbon::createFromDate($año, 12, 31)->endOfYear();
            
            $usuariosQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
            $vehiculosQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
            $alertasQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
            $formulariosQuery->whereBetween('created_at', [$startOfYear, $endOfYear]);
        }
        
        // Contar totales (sin filtro de año para mostrar el total general)
        $cantidadUsuarios = User::count();
        $cantidadVehiculos = Vehicle::count();
        $cantidadAlertas = Alert::count();
        $cantidadFormularios = PreoperationalForm::count();
        
        // Contar filtrados por el año o mes seleccionado
        $usuariosEsteMes = $usuariosQuery->count();
        $vehiculosEsteMes = $vehiculosQuery->count();
        $alertasEsteMes = $alertasQuery->count();
        $formulariosEsteMes = $formulariosQuery->count();
        
        // Datos para la gráfica (valores para el año seleccionado)
        $datosGrafica = [
            'usuarios' => $this->obtenerDatosHistoricos(User::class, $año),
            'vehiculos' => $this->obtenerDatosHistoricos(Vehicle::class, $año),
            'alertas' => $this->obtenerDatosHistoricos(Alert::class, $año),
            'formularios' => $this->obtenerDatosHistoricos(PreoperationalForm::class, $año)
        ];
        
        // Obtener lista de años disponibles para el selector
        $añosDisponibles = $this->obtenerAñosDisponibles();
        
        return view('dashboard.sst', compact(
            'cantidadUsuarios',
            'usuariosEsteMes',
            'cantidadVehiculos',
            'vehiculosEsteMes',
            'cantidadAlertas',
            'alertasEsteMes',
            'cantidadFormularios',
            'formulariosEsteMes',
            'desde',
            'hasta',
            'datosGrafica',
            'año',
            'añosDisponibles'
        ));
    }

    /**
     * Obtiene datos históricos para la gráfica para un año específico
     */
    private function obtenerDatosHistoricos($modelo, $año)
    {
        $datosMensuales = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $inicioMes = Carbon::create($año, $mes, 1)->startOfMonth();
            $finMes = Carbon::create($año, $mes, 1)->endOfMonth();

            $conteo = $modelo::whereBetween('created_at', [$inicioMes, $finMes])->count();
            $datosMensuales[] = $conteo;
        }

        return $datosMensuales;
    }
    
    /**
     * Obtiene los años disponibles para el selector
     * basado en los registros existentes en la base de datos
     */
    private function obtenerAñosDisponibles()
    {
        // Obtener el año más antiguo de cada modelo
        $añoMasAntiguoUsuarios = User::min('created_at');
        $añoMasAntiguoVehiculos = Vehicle::min('created_at');
        $añoMasAntiguoAlertas = Alert::min('created_at');
        $añoMasAntiguoFormularios = PreoperationalForm::min('created_at');
        
        // Convertir a objetos Carbon
        $fechas = array_filter([
            $añoMasAntiguoUsuarios ? Carbon::parse($añoMasAntiguoUsuarios) : null,
            $añoMasAntiguoVehiculos ? Carbon::parse($añoMasAntiguoVehiculos) : null,
            $añoMasAntiguoAlertas ? Carbon::parse($añoMasAntiguoAlertas) : null,
            $añoMasAntiguoFormularios ? Carbon::parse($añoMasAntiguoFormularios) : null
        ]);
        
        // Si no hay fechas, devolver solo el año actual
        if (empty($fechas)) {
            return [Carbon::now()->year];
        }
        
        // Encontrar el año más antiguo
        $añoMasAntiguo = min(array_map(function($fecha) {
            return $fecha->year;
        }, $fechas));
        
        // Crear array de años desde el más antiguo hasta el actual
        $añoActual = Carbon::now()->year;
        $años = [];
        
        for ($año = $añoMasAntiguo; $año <= $añoActual; $año++) {
            $años[] = $año;
        }
        
        // Añadir el próximo año para planificación
        $años[] = $añoActual + 1;
        
        return $años;
    }
}
