@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Catálogo de Vehículos</h1>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">Registrar vehículo</a>
    </div>

    <div class="row">
                @forelse($vehicles as $vehicle)
                    <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                    @if (session('uploaded_image'))
                        <div class="alert alert-success">
                            Imagen subida correctamente:
                            <br>
                            <img src="{{ asset('images/' . session('uploaded_image')) }}" width="300" class="img-thumbnail mt-2">
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $vehicle->brand->name ?? 'Marca desconocida' }} 
                            {{ $vehicle->model->name ?? 'Modelo desconocido' }}
                        </h5>
                        <p class="card-text">Tipo: {{ $vehicle->model->type->name ?? 'Tipo no especificado' }}</p>
                        <p class="card-text">Año: {{ $vehicle->model_year }}</p>
                        <p class="card-text">Color: {{ $vehicle->color }}</p>
                        <p class="card-text">Placa: {{ $vehicle->plate }}</p>
                        <p class="card-text">Kilometraje: {{ number_format($vehicle->mileage) }} km</p>
                        <p class="card-text"><small class="text-muted">SOAT: {{ $vehicle->soat }} ({{ $vehicle->soat_status ? 'Vigente' : 'Vencido' }})</small></p>
                        <p class="card-text"><small class="text-muted">Revisión Tecnomecánica: {{ $vehicle->mechanical_review }} ({{ $vehicle->mechanical_review_status ? 'Aprobada' : 'Vencida' }})</small></p>
                    </div>
                </div>
            </div>
        @empty
            <p>No hay vehículos registrados.</p>
        @endforelse
    </div>
</div>
@endsection