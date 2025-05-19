@extends('layouts.app')

@section('title', 'Dashboard Conductor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboards.css') }}">
@endpush

@section('content')
<div class="container-fluid main-content-bg py-4">
    <!-- Contenido principal -->
    <div class="px-2 px-md-4">
        <h2 class="mb-4">Bienvenido, {{ Auth::user()->fullName }}</h2>
        <div class="row">
            <!-- Tarjetas de estadísticas -->
            <div class="col-6 col-md-4 mb-4">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Vehículos Activos</h5>
                        <p class="display-4">{{ $vehiculosActivos }}</p>
                        <p class="text-muted">Vehículos disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-4">
                <div class="card h-100 border-success">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">Mis Formularios</h5>
                        <p class="display-4">{{ $totalFormularios }}</p>
                        <p class="text-muted">Formularios completados</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-4">
                <div class="card h-100 border-info">
                    <div class="card-body text-center">
                        <h5 class="card-title text-info">Formularios del Mes</h5>
                        <p class="display-4">{{ $formulariosDelMes }}</p>
                        <p class="text-muted">Este mes</p>
                    </div>
                </div>
            </div>

            <!-- Accesos rápidos -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Formularios Preoperacionales Recientes</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filtros de fecha -->
                        <form method="GET" action="{{ route('conductor.dashboard') }}" class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="start_date" class="form-label">Fecha Inicial</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ $start_date ?? '' }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="end_date" class="form-label">Fecha Final</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ $end_date ?? '' }}">
                            </div>
                            <div class="col-12 col-md-3">
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
                            <div class="col-12 col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <div class="table-responsive">
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
                                            <td colspan="4">
                                                <p class="text-muted">No has diligenciado formularios recientemente.</p>
                                            </td>
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
                                            <td colspan="4"><p class="text-muted">No hay datos para mostrar.</p></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Accesos Rápidos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6 mb-2">
                                <a href="{{ route('conductor.preoperational-forms.index') }}" class="btn btn-outline-success w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-list-check me-2"></i> Mis Formularios
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('conductor.vehicles.index') }}" class="btn btn-outline-info w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-truck me-2"></i> Vehículos
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('conductor.answers.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check2-square me-2"></i> Mis Respuestas
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('conductor.observations.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clipboard-plus me-2"></i> Mis observaciones
                                </a>
                            </div>
                        </div>
                    </div>
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