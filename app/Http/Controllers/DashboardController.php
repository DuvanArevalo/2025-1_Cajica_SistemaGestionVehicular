<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Alert;
use App\Models\PreoperationalForm;
use App\Models\Observation;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        // Filtros por rango de fechas
        $users = User::when($desde && $hasta, function($query) use ($desde, $hasta) {
            return $query->whereBetween('created_at', [$desde, $hasta]);
        })->orderBy('created_at', 'desc')->get();

        $vehicles = Vehicle::when($desde && $hasta, function($query) use ($desde, $hasta) {
            return $query->whereBetween('created_at', [$desde, $hasta]);
        })->orderBy('created_at', 'desc')->get();

        $alerts = Alert::when($desde && $hasta, function($query) use ($desde, $hasta) {
            return $query->whereBetween('created_at', [$desde, $hasta]);
        })->orderBy('created_at', 'desc')->get();

        $forms = PreoperationalForm::when($desde && $hasta, function($query) use ($desde, $hasta) {
            return $query->whereBetween('created_at', [$desde, $hasta]);
        })->orderBy('created_at', 'desc')->get();

        $observations = Observation::when($desde && $hasta, function($query) use ($desde, $hasta) {
            return $query->whereBetween('created_at', [$desde, $hasta]);
        })->orderBy('created_at', 'desc')->get();

        return view('dashboard.admin', compact(
            'users', 'vehicles', 'alerts', 'forms', 'observations', 'desde', 'hasta'
        ));
    }
}