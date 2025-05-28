@extends('layouts.app')

@section('title', 'Detalles del Modelo de Vehículo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return
            route="{{ Auth::user()->role->name }}.vehicle-models.index"
            class="mb-3"
            text="Volver al listado" />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">

                    <div class="row">
                        <!-- Fecha de creación -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Fecha de Creación:</h6>
                            <p>{{ optional($model->created_at)->format('d/m/Y H:i:s') ?? 'No disponible' }}</p>
                        </div>
                        <!-- Última actualización -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                            <p>{{ optional($model->updated_at)->format('d/m/Y H:i:s') ?? 'No disponible' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre del modelo -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Modelo:</h6>
                            <p>{{ $model->name }}</p>
                        </div>
                        <!-- Marca asociada -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Marca:</h6>
                            <p>{{ $model->brand->name }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">
                                Vehículos Asociados: {{ $model->vehicles->count() }}
                            </h6>

                            @if($model->vehicles->isEmpty())
                                <p>No hay vehículos asociados.</p>
                            @else
                                <ul class="list-unstyled">
                                    @foreach($model->vehicles as $vehicle)
                                        <li>
                                            {{ $vehicle->plate ?? $vehicle->id }} - {{ $vehicle->brand->name ?? $vehicle->id }} {{ $vehicle->model->name ?? $vehicle->id }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route(Auth::user()->role->name .'.vehicle-models.edit', $model->id) }}"
                           class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
