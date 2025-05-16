@extends('layouts.app')

@section('content')
<style>
@media (prefers-color-scheme: dark) {
    .card.bg-dark-mode, .card.bg-dark-mode .card-header, .card.bg-dark-mode .card-body, .table.bg-dark-mode {
        background-color:#525658 !important;
        color: #fff !important;
    }
    .table.bg-dark-mode th, .table.bg-dark-mode td {
        background-color: #525658 !important;
        color: #fff !important;
    }
}
</style>
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
            <div class="card h-100 bg-dark-mode">
                <div class="card-header bg-dark-mode">
                    Formularios Preoperacionales Recientes
                </div>
                <div class="card-body bg-dark-mode">
                    <table class="table table-sm table-hover bg-dark-mode">
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
@endsection