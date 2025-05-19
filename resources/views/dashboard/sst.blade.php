@extends('layouts.app')

@section('title', 'Dashboard SST')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboards.css') }}">
@endpush

@section('content')
<div class="container-fluid main-content-bg py-4">
    <!-- Contenido principal -->
    <div class="px-2 px-md-4">
        <h2 class="mb-4">Panel de SST</h2>
        <div class="row">
            <!-- Tarjetas resumen -->
             <!-- Usuarios -->
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text display-4">{{ $cantidadUsuarios }}</p>  {{-- Total usuarios --}}
                        <p class="card-text">+{{ $usuariosEsteMes }} este mes</p>   {{-- Nuevos usuarios este mes --}}
                    </div>
                </div>
            </div>
            <!-- Vehiculos -->
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <h5 class="card-title">Vehículos</h5>
                        <p class="card-text display-4">{{ $cantidadVehiculos }}</p>
                        <p class="card-text">+{{ $vehiculosEsteMes }} este mes</p>
                    </div>
                </div>
            </div>
            <!-- Alertas -->
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <h5 class="card-title">Alertas</h5>
                        <p class="card-text display-4">{{ $cantidadAlertas }}</p>
                        <p class="card-text">+{{ $alertasEsteMes }} este mes</p>
                    </div>
                </div>
            </div>
            <!-- Formularios -->
            <div class="col-6 col-md-3 mb-4">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <h5 class="card-title">Formularios</h5>
                        <p class="card-text display-4">{{ $cantidadFormularios }}</p>
                        <p class="card-text">+{{ $formulariosEsteMes }} este mes</p>
                    </div>
                </div>
            </div>

            <!-- Filtro de fechas -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Filtros</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('sst.dashboard') }}" class="row g-3">
                            <!-- Selector de año -->
                            <div class="col-6 col-md-3 mb-2">
                                <label for="añoSelector" class="form-label">Año:</label>
                                <select id="añoSelector" name="año" class="form-select" aria-label="Selecciona un año">
                                    @foreach($añosDisponibles as $añoOption)
                                        <option value="{{ $añoOption }}" {{ $año == $añoOption ? 'selected' : '' }}>{{ $añoOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Selector de mes -->
                            <div class="col-6 col-md-3 mb-2">
                                <label for="mesSelector" class="form-label">Mes:</label>
                                <select id="mesSelector" name="mes" class="form-select" aria-label="Selecciona un mes">
                                    <option value="">Selecciona un mes...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        @php
                                            $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
                                            $nombreMes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][$i-1];
                                        @endphp
                                        <option value="{{ $mes }}" {{ (request('mes') == $mes || (empty(request('mes')) && date('m') == $mes)) ? 'selected' : '' }}>
                                            {{ $nombreMes }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Inputs de fecha -->
                            <div class="col-6 col-md-3 mb-2">
                                <label for="desde" class="form-label">Desde:</label>
                                <input type="date" id="desde" name="desde" class="form-control" value="{{ $desde ?? '' }}">
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <label for="hasta" class="form-label">Hasta:</label>
                                <input type="date" id="hasta" name="hasta" class="form-control" value="{{ $hasta ?? '' }}">
                            </div>

                            <!-- Botones -->
                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('sst.dashboard') }}" class="btn btn-outline-secondary">Limpiar filtros</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Gráficos y secciones -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        Estadísticas por mes
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 50vh; min-height: 300px;">
                            <canvas id="vehiculosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        Accesos Rápidos
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6 mb-2">
                                <a href="{{ route('sst.users.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people me-2"></i> Usuarios
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('sst.vehicles.index') }}" class="btn btn-outline-success w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-truck me-2"></i> Vehículos
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('sst.alerts.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Alertas
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('sst.preoperational-forms.index') }}" class="btn btn-outline-info w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clipboard-check me-2"></i> Formularios
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('vehiculosChart').getContext('2d');
        const datosGrafica = JSON.parse('{!! json_encode($datosGrafica) !!}');
        const añoSeleccionado = '{!! $año !!}';

        // Nombres de los meses
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Usuarios',
                        data: datosGrafica.usuarios,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    },
                    {
                        label: 'Vehículos',
                        data: datosGrafica.vehiculos,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    },
                    {
                        label: 'Alertas',
                        data: datosGrafica.alertas,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    },
                    {
                        label: 'Formularios',
                        data: datosGrafica.formularios,
                        backgroundColor: 'rgba(13, 202, 240, 0.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: `Estadísticas del año ${añoSeleccionado}`,
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Script para alternar modo oscuro y actualización automática
        document.addEventListener('DOMContentLoaded', function () {
            // Actualizar al cambiar el año
            document.getElementById('añoSelector').addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
@endpush