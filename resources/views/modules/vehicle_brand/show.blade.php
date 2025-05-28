@extends('layouts.app')

@section('title', 'Detalles de la Marca de Vehículo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return
            route="{{ Auth::user()->role->name }}.vehicle-brands.index"
            class="mb-3"
            text="Volver al listado" />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- Fecha de creación -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Fecha de Creación:</h6>
                            <p>{{ optional($brand->created_at)->format('d/m/Y H:i:s') ?? 'No disponible' }}</p>
                        </div>

                        <!-- Última actualización -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                            <p>{{ optional($brand->updated_at)->format('d/m/Y H:i:s') ?? 'No disponible' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Marca:</h6>
                            <p>{{ $brand->name }}</p>
                        </div>

                        <!-- Conteo de vehículos -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Modelos Asociados: {{ $brand->vehicleModels->count() }}</h6>
                            
                            @if($brand->vehicleModels->isEmpty())
                                <p>No hay modelos asociados.</p>
                            @else
                                <ul class="list-unstyled">
                                    @foreach($brand->vehicleModels as $model)
                                        <li>
                                            {{-- Si tienes ruta para editar/ver el modelo, podrías enlazarla aquí --}}
                                            {{ $model->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route(Auth::user()->role->name .'.vehicle-brands.edit', $brand->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
