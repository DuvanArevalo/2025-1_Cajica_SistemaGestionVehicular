@extends('layouts.app')

@section('title', 'Detalle de Alerta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Fecha de Creación:</h6>
                                <p>{{ $alert->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Formulario Preoperacional:</h6>
                                <p>ID: {{ $alert->preoperationalForm->id }} - Vehículo: {{ $alert->preoperationalForm->vehicle->plate }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Estado de Alerta:</h6>
                                <p>{{ $alert->alertStatus->type }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Pregunta:</h6>
                                <p>{{ $alert->answer->question->text }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Respuesta:</h6>
                                <p>{{ $alert->answer->value ? 'Sí' : 'No' }}</p>
                            </div>
                        </div>
                    </div>
                    @if($alert->observation)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Observación:</h6>
                                <p>{{ $alert->observation->text }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route(Auth::user()->role->name . '.alerts.edit', $alert->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Cambiar Estado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection