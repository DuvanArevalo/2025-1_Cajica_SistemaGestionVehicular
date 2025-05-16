@extends('layouts.app')

@section('content')
<div class="container main-content-bg py-4">
    <h2 class="mb-4">Bienvenido, {{ Auth::user()->name1 }} (Conductor)</h2>

    <!-- Accesos rápidos -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <a class="btn btn-outline-primary w-100" style="pointer-events: none;">
                <i class="bi bi-clipboard-plus"></i> Nuevo Formulario Preoperacional
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a class="btn btn-outline-success w-100" style="pointer-events: none;">
                <i class="bi bi-list-check"></i> Mis Formularios
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a class="btn btn-outline-info w-100" style="pointer-events: none;">
                <i class="bi bi-truck"></i> Mis Vehículos
            </a>
        </div>
    </div>

    <!-- Botones solo visuales para alertas -->
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
                <div class="card-header bg-light">
                    Formularios Preoperacionales Recientes
                </div>
                <div class="card-body">
                    <p class="text-muted">No hay datos para mostrar.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection