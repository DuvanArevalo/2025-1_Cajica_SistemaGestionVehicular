@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Contenedor del título y botón de volver --}}
    <a href="{{ route('catalogo.index') }}" class="btn btn-light border border-dark text-dark px-4 py-2">
            ← Volver
        </a>

    {{-- Tarjeta con sombra y detalles organizados --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Fecha de Creación:</strong> {{ $vehicle->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Última Actualización:</strong> {{ $vehicle->updated_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tipo de Vehículo:</strong> {{ $vehicle->vehicleType->name ?? 'Desconocido' }}</p>
                    <p><strong>Marca:</strong> {{ $vehicle->brand->name ?? 'Desconocida' }}</p>
                    <p><strong>Modelo:</strong> {{ $vehicle->model->name ?? 'Desconocido' }}</p>
                    <p><strong>Año:</strong> {{ $vehicle->model_year }}</p>
                    <p><strong>Color:</strong> {{ $vehicle->color }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Número de Ruedas:</strong> {{ $vehicle->wheel_count }}</p>
                    <p><strong>Placa:</strong> {{ $vehicle->plate }}</p>
                    <p><strong>Kilometraje:</strong> {{ number_format($vehicle->mileage) }} km</p>
                    <p><strong>SOAT:</strong> 
                        <span class="badge {{ $vehicle->soat_status ? 'bg-success' : 'bg-danger' }}">
                            {{ $vehicle->soat_status ? 'Vigente' : 'Vencido' }}
                        </span>
                    </p>
                    <p><strong>Tecnomecánica:</strong> 
                        <span class="badge {{ $vehicle->mechanical_review_status ? 'bg-success' : 'bg-danger' }}">
                            {{ $vehicle->mechanical_review_status ? 'Aprobada' : 'Vencida' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Botón Editar centrado sin margen --}}
        <div class="card-footer bg-light text-center">
            <a href="{{ route('vehiculos.edit', $vehicle->id) }}" class="btn btn-warning px-5 py-2">
                Editar ✏️
            </a>
        </div>
    </div>

</div>
@endsection