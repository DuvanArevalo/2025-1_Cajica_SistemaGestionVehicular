@extends('layouts.app')

@section('content')
<style>
/* Estilos base para ambos modos */
.card {
    background-color: #ffffff;
    color: #000000;
}

.card .card-header {
    background-color: #f8f9fa;
    color: #000000;
    border-bottom: 1px solid #dee2e6;
}

.table {
    color: #000000;
}

.table th {
    background-color: #f8f9fa;
    color: #000000;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    background-color: #ffffff;
    color: #000000;
    border-top: 1px solid #dee2e6;
}

.table tr:hover td {
    background-color: #f8f9fa;
}

/* Estilos para modo oscuro */
[data-bs-theme="dark"] .card {
    background-color: #2d2d2d !important;
    color: #ffffff !important;
}

[data-bs-theme="dark"] .card .card-header {
    background-color: #363636 !important;
    color: #ffffff !important;
    border-bottom: 1px solid #404040;
}

[data-bs-theme="dark"] .table {
    color: #ffffff !important;
}

[data-bs-theme="dark"] .table th {
    background-color: #363636 !important;
    color: #ffffff !important;
    border-bottom: 2px solid #404040;
}

[data-bs-theme="dark"] .table td {
    background-color: #2d2d2d !important;
    color: #ffffff !important;
    border-top: 1px solid #404040;
}

[data-bs-theme="dark"] .table tr:hover td {
    background-color: #363636 !important;
}

[data-bs-theme="dark"] .text-muted {
    color: #a0a0a0 !important;
}
</style>

<div class="container main-content-bg py-4">
    <h2 class="mb-4">Bienvenido, {{ Auth::user()->name1 }} (Conductor)</h2>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Vehículos Activos</h5>
                    <p class="display-4">0</p>
                    <p class="text-muted">Vehículos disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Mis Formularios</h5>
                    <p class="display-4">0</p>
                    <p class="text-muted">Formularios completados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Formularios del Mes</h5>
                    <p class="display-4">0</p>
                    <p class="text-muted">Este mes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <button class="btn btn-outline-primary w-100" disabled>
                <i class="bi bi-clipboard-plus"></i> Nuevo Formulario Preoperacional
            </button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-outline-success w-100" disabled>
                <i class="bi bi-list-check"></i> Mis Formularios
            </button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-outline-info w-100" disabled>
                <i class="bi bi-truck"></i> Mis Vehículos
            </button>
        </div>
    </div>

    <!-- Botones de alertas -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <button class="btn btn-outline-warning w-100" disabled>
                <i class="bi bi-exclamation-triangle"></i> Ver Alertas
            </button>
        </div>
        <div class="col-md-6 mb-3">
            <button class="btn btn-warning w-100" disabled>
                <i class="bi bi-plus-circle"></i> Generar Alerta
            </button>
        </div>
    </div>

    <!-- Resumen de formularios recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header">
                    Formularios Preoperacionales Recientes
                </div>
                <div class="card-body">
                    <!-- Filtros de fecha -->
                    <form method="GET" action="{{ route('conductor.dashboard') }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Fecha Inicial</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ $start_date ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">Fecha Final</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ $end_date ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="month_select" class="form-label">Seleccionar Mes</label>
                                    <select class="form-select" id="month_select" name="month_select">
                                        <option value="">Seleccione un mes</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ request('month_select') == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($i)->locale('es')->monthName }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Vehículo</th>
                                <th>Fecha</th>
                                <th>Kilometraje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($preoperationalForms) && $preoperationalForms->isEmpty())
                                <tr>
                                    <td colspan="4"><p class="text-muted text-white-50">No has diligenciado formularios recientemente.</p></td>
                                </tr>
                            @elseif(isset($preoperationalForms))
                                @foreach($preoperationalForms as $form)
                                    <tr>
                                        <td>{{ $form->vehicle->plate ?? 'N/A' }}</td>
                                        <td>{{ $form->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $form->new_mileage }}</td>
                                        <td>
                                            <a href="{{ route('conductor.preoperational-forms.show', $form->id) }}" class="btn btn-sm btn-outline-info">
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4"><p class="text-muted text-white-50">No hay datos para mostrar.</p></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-govco-footer />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthSelect = document.getElementById('month_select');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        monthSelect.addEventListener('change', function () {
            const selectedMonth = parseInt(this.value);
            if (selectedMonth) {
                const currentYear = new Date().getFullYear();
                const startDate = new Date(currentYear, selectedMonth - 1, 1);
                const endDate = new Date(currentYear, selectedMonth, 0);

                startDateInput.value = startDate.toISOString().split('T')[0];
                endDateInput.value = endDate.toISOString().split('T')[0];
            }
        });
    });
</script>
@endsection