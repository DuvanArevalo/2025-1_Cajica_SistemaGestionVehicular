@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="d-flex" style="min-height: 100vh;">
    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4 main-content-bg">
        <h2 class="mb-4">Panel de Administración</h2>
        <div class="row">
            <!-- Tarjetas resumen -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text display-4">120</p>
                        <small>+5 este mes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <h5 class="card-title">Vehículos</h5>
                        <p class="card-text display-4">45</p>
                        <small>+2 este mes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <h5 class="card-title">Alertas</h5>
                        <p class="card-text display-4">8</p>
                        <small>+1 este mes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <h5 class="card-title">Formularios</h5>
                        <p class="card-text display-4">32</p>
                        <small>+3 este mes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtro de fechas -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 p-3 bg-white rounded shadow-sm border">
                    <label for="desde" class="form-label mb-0 me-2 fw-bold">Desde:</label>
                    <input type="date" id="desde" name="desde" class="form-control" style="max-width: 180px;" value="{{ $desde ?? '' }}">
                    <label for="hasta" class="form-label mb-0 ms-3 me-2 fw-bold">Hasta:</label>
                    <input type="date" id="hasta" name="hasta" class="form-control" style="max-width: 180px;" value="{{ $hasta ?? '' }}">
                    <button type="submit" class="btn btn-primary ms-3">Filtrar</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary ms-2">Limpiar filtros</a>
                </form>
            </div>
        </div>

        <!-- Gráficos y secciones -->
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        Estadísticas de Vehículos
                    </div>
                    <div class="card-body">
                        <canvas id="vehiculosChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        Accesos Rápidos
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body d-flex align-items-center justify-content-center p-2">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">Gestionar Usuarios</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body d-flex align-items-center justify-content-center p-2">
                                        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-success w-100">Gestionar Vehículos</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body d-flex align-items-center justify-content-center p-2">
                                        <a href="{{ route('admin.alerts.index') }}" class="btn btn-outline-warning w-100">Ver Alertas</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body d-flex align-items-center justify-content-center p-2">
                                        <a href="{{ route('admin.preoperational-forms.index') }}" class="btn btn-outline-info w-100">Formularios Preoperacionales</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para el gráfico (usa Chart.js) -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('vehiculosChart').getContext('2d');
    var vehiculosChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Vehículos registrados',
                data: [5, 8, 12, 15, 20, 25],
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    // Script para alternar modo oscuro
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('darkModeToggle');
        const icon = document.getElementById('darkModeIcon');
        toggle && toggle.addEventListener('click', function () {
            const html = document.documentElement;
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-fill');
                toggle.innerHTML = '<i class="bi bi-moon-fill" id="darkModeIcon"></i> Modo oscuro';
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                icon.classList.remove('bi-moon-fill');
                icon.classList.add('bi-sun-fill');
                toggle.innerHTML = '<i class="bi bi-sun-fill" id="darkModeIcon"></i> Modo claro';
            }
        });
    });
</script>
@endpush

<!-- Estilos personalizados para la sidebar y contenido principal -->
@push('styles')
<style>
/* Fondo claro por defecto */
.main-content-bg {
    background: #f8f9fa;
}

/* Modo oscuro para toda la página */
[data-bs-theme="dark"] .main-content-bg {
    background: #181a1b !important;
}

/* Fondo negro para tarjetas y formularios en modo oscuro */
[data-bs-theme="dark"] .card,
[data-bs-theme="dark"] .bg-white,
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .card-header.bg-light {
    background-color: #23272b !important;
    color: #fff !important;
    border-color: #23272b !important;
}

/* Color de texto para inputs y labels en modo oscuro */
[data-bs-theme="dark"] .form-label,
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] label,
[data-bs-theme="dark"] input,
[data-bs-theme="dark"] select,
[data-bs-theme="dark"] option {
    color: #fff !important;
    background-color: #23272b !important;
    border-color: #444 !important;
}

/* Botones outline conservan su color en modo oscuro */
[data-bs-theme="dark"] .btn-outline-primary {
    color: #0d6efd !important;
    border-color: #0d6efd !important;
}
[data-bs-theme="dark"] .btn-outline-success {
    color: #198754 !important;
    border-color: #198754 !important;
}
[data-bs-theme="dark"] .btn-outline-warning {
    color: #ffc107 !important;
    border-color: #ffc107 !important;
}
[data-bs-theme="dark"] .btn-outline-info {
    color: #0dcaf0 !important;
    border-color: #0dcaf0 !important;
}
[data-bs-theme="dark"] .btn-outline-primary:hover,
[data-bs-theme="dark"] .btn-outline-success:hover,
[data-bs-theme="dark"] .btn-outline-warning:hover,
[data-bs-theme="dark"] .btn-outline-info:hover {
    background-color: #fff !important;
    color: #23272b !important;
}
</style>
@endpush
@endsection

