<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\AlertStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlertStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AlertStatus::query();

        // Filtrar según el tipo seleccionado
        $filterType = $request->filter_type ?? 'type';

        // Filtro por nombre del estado de alerta
        if ($filterType == 'type' && $request->filled('type_search')) {
            $buscado = strtolower(trim($request->type_search));
            $query->whereRaw('LOWER(type) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por descripción
        if ($filterType == 'description' && $request->filled('description_search')) {
            $buscado = strtolower(trim($request->description_search));
            $query->whereRaw('LOWER(description) LIKE ?', ["%{$buscado}%"]);
        }

        // Filtro por rango de fechas
        if ($filterType == 'date_range') {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        // Paginamos y mantenemos los filtros en la URL
        $alertStatuses = $query
            ->paginate(20)
            ->appends($request->only(['filter_type', 'type_search', 'description_search', 'date_from', 'date_to']));

        return view('modules.alert_status.index', compact('alertStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.alert_status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255|unique:alert_statuses',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.alert-statuses.create')
                ->withErrors($validator)
                ->withInput();
        }

        AlertStatus::create($request->all());

        return redirect()->route(Auth::user()->role->name .'.alert-statuses.index')
            ->with('success', 'Estado de alerta creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AlertStatus $alertStatus)
    {
        return view('modules.alert_status.show', compact('alertStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AlertStatus $alertStatus)
    {
        return view('modules.alert_status.edit', compact('alertStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AlertStatus $alertStatus)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255|unique:alert_statuses,type,' . $alertStatus->id,
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route(Auth::user()->role->name .'.alert-statuses.edit', $alertStatus->id)
                ->withErrors($validator)
                ->withInput();
        }

        $alertStatus->update($request->all());

        return redirect()->route(Auth::user()->role->name .'.alert-statuses.index')
            ->with('success', 'Estado de alerta: '.$alertStatus->type.' actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AlertStatus $alertStatus)
    {
        // Verificar si hay alertas asociadas a este estado
        if ($alertStatus->alerts()->count() > 0) {
            return redirect()->route(Auth::user()->role->name .'.alert-statuses.index')
                ->with('error', 'No se puede eliminar este Estado porque hay alertas asociadas.');
        }

        $alertStatus->delete();
        return redirect()->route(Auth::user()->role->name .'.alert-statuses.index')
            ->with('success', 'Estado de alerta eliminado exitosamente.');
    }
}
