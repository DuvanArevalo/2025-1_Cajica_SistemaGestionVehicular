@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('catalogo.index') }}" class="btn btn-light border border-dark text-dark px-4 py-2">
        ← Volver
    </a>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Editar Vehículo</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('vehiculos.update', $vehicle->id) }}" method="POST">
                @csrf
                @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="brand_id" class="form-label">Marca:</label>
                    <select id="brand_id" name="brand_id" class="form-control" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                            <label for="vehicle-types_id" class="form-label">Tipo de Vehículo:</label>
                            <select id="vehicle-types_id" name="vehicle-types_id" class="form-control" required>
                        @foreach($vehicleTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                            </select>
                    </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                                <label for="vehicle-models_id" class="form-label">Modelo:</label>
                                <select id="vehicle-models_id" name="vehicle-models_id" class="form-control" required>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                <div class="col-md-6">
                    <label for="model_year" class="form-label">Año:</label>
                    <input type="number" id="model_year" name="model_year" class="form-control" value="{{ $vehicle->model_year }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                        <label for="wheel_count">Número de ruedas</label>
                        <input type="number" name="wheel_count" maxlength="2" class="form-control" required>
                    </div>
                <div class="col-md-6">
                    <label for="color" class="form-label">Color:</label>
                    <input type="text" id="color" name="color" class="form-control" value="{{ $vehicle->color }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="plate" class="form-label">Placa:</label>
                    <input type="text" id="plate" name="plate" class="form-control" value="{{ $vehicle->plate }}" required>
                </div>
                <div class="col-md-6">
                    <label for="mileage" class="form-label">Kilometraje:</label>
                    <input type="number" id="mileage" name="mileage" class="form-control" value="{{ $vehicle->mileage }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                        <label for="soat">Fecha SOAT</label>
                        <input type="date" name="soat" class="form-control" required>
                    </div>
                <div class="col-md-6">
                    <label for="soat_status" class="form-label">Estado SOAT:</label>
                    <select id="soat_status" name="soat_status" class="form-control">
                        <option value="1" {{ $vehicle->soat_status ? 'selected' : '' }}>Vigente</option>
                        <option value="0" {{ !$vehicle->soat_status ? 'selected' : '' }}>Vencido</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                        <label for="mechanical_review">Fecha revisión mecánica</label>
                        <input type="date" name="mechanical_review" class="form-control" required>
                    </div>
                <div class="col-md-6">
                    <label for="mechanical_review_status" class="form-label">Estado Tecnomecánica:</label>
                    <select id="mechanical_review_status" name="mechanical_review_status" class="form-control">
                        <option value="1" {{ $vehicle->mechanical_review_status ? 'selected' : '' }}>Aprobada</option>
                        <option value="0" {{ !$vehicle->mechanical_review_status ? 'selected' : '' }}>Vencida</option>
                    </select>
                </div>
            </div>

                {{-- Botón guardar cambios --}}
                <div class="text-center">
                    <button type="submit" class="btn btn-warning px-5 py-2">
                        Guardar Cambios ✏️
                    </button>
                </div>
                {{-- Botón de eliminación --}}
            <form action="{{ route('vehiculos.destroy', $vehicle->id) }}" method="POST" class="mt-3 text-center">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-5 py-2">Eliminar Vehículo ❌</button>
            </form>

            </form>
        </div>
    </div>
</div>
@endsection