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

        return view('dashboard.conductor', [
            'vehiculosActivos' => 0,
            'totalFormularios' => PreoperationalForm::where('user_id', $userId)->count(),
            'formulariosDelMes' => PreoperationalForm::where('user_id', $userId)
                ->whereMonth('created_at', Carbon::now()->month)->count(),
            'preoperationalForms' => $query->get(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);
    }
}