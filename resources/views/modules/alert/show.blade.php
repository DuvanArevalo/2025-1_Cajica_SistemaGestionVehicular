@extends('layouts.app')

@section('title', 'Detalles de la Alerta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.alerts.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                                <p>{{ $alert->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Formulario:</h6>
                                <p>Formulario #{{ $alert->form_id }} - {{ $alert->preoperationalForm->vehicle->plate }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Estado:</h6>
                                <p><span class="badge bg-primary">{{ $alert->alertStatus->type }}</span></p>
                            </div>
                        </div>
                    </div>
                    
                    @if($alert->answer_id)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Tipo de Alerta:</h6>
                                    <p>Generada por respuesta negativa</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Pregunta:</h6>
                                    <p>{{ $alert->answer->question->text }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Respuesta:</h6>
                                    <p><span class="badge bg-danger">No</span></p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($alert->observation_id)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Tipo de Alerta:</h6>
                                    <p>Generada por observación</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Sección:</h6>
                                    <p>{{ $alert->observation->section->name }}</p>
                                </div>
                            </div>
                        </div>
                        
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
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection