<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\PreoperationalForm;
use Carbon\Carbon;

class ConductorController extends Controller
{
    /**
     * Muestra el dashboard del conductor.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $month = $request->input('month_select');
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : Carbon::now()->subMonth()->startOfDay();

        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : Carbon::now()->endOfDay();

        $query = PreoperationalForm::where('user_id', $userId)
            ->with('vehicle')
            ->orderBy('created_at', 'desc');

        // Si se selecciona un mes, filtra por ese mes y año actual
        if ($month) {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', Carbon::now()->year);
        } else {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Obtener el número total de vehículos
        $vehiculosActivos = Vehicle::count();

        // Obtener el número total de formularios del usuario
        $totalFormularios = PreoperationalForm::where('user_id', $userId)->count();

        // Definir el mes actual
        $currentMonth = Carbon::now()->month;

        // Obtener el número de formularios del mes actual del usuario
        $formulariosDelMes = PreoperationalForm::where('user_id', $userId)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        return view('dashboard.conductor', [
            'vehiculosActivos' => $vehiculosActivos,
            'totalFormularios' => $totalFormularios,
            'formulariosDelMes' => $formulariosDelMes,
            'preoperationalForms' => $query->get(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);
    }
}