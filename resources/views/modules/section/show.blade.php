@extends('layouts.app')

@section('title', 'Detalles de la Sección')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return
            route="{{ Auth::user()->role->name }}.sections.index"
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
                                <p>{{ $section->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Nombre:</h6>
                                <p>{{ $section->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                                <p>{{ $section->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Tipos de Vehículo Asociados:</h6>
                                @if($section->vehicleTypes->count() > 0)
                                    <ul>
                                        @foreach($section->vehicleTypes as $vehicleType)
                                            <li>{{ $vehicleType->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No hay tipos de vehículo asociados.</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Preguntas Asociadas:</h6>
                                <p>{{ $section->questions->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route(Auth::user()->role->name . '.sections.edit', $section->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection