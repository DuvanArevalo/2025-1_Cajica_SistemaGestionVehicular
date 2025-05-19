@extends('layouts.app')

@section('title', 'Detalle de Observación')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.observations.index" 
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
                                <p>{{ $observation->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Formulario:</h6>
                                <p>Formulario #{{ $observation->form->id }} - {{ $observation->form->vehicle->plate }} {{ $observation->form->vehicle->brand->name }} {{ $observation->form->vehicle->model->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Sección:</h6>
                                <p>{{ $observation->section->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Observación:</h6>
                                <p>{{ $observation->text }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                                <p>{{ $observation->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Alertas Asociadas:</h6>
                                <p>{{ $observation->alerts->count() }}</p>
                            </div>
                        </div>
                    </div>
                    @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route(strtolower(Auth::user()->role->name) . '.observations.edit', $observation->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection