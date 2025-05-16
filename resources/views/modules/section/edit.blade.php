@extends('layouts.app')

@section('title', 'Editar Sección')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Editar Sección</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.sections.update', $section->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Sección</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $section->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipos de Vehículo</label>
                            <div class="card">
                                <div class="card-body">
                                    @error('vehicle_types')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="row">
                                        @foreach($vehicleTypes as $vehicleType)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="vehicle_types[]" 
                                                        id="vehicle_type_{{ $vehicleType->id }}" 
                                                        value="{{ $vehicleType->id }}"
                                                        {{ (is_array(old('vehicle_types')) && in_array($vehicleType->id, old('vehicle_types'))) || 
                                                           (old('vehicle_types') === null && in_array($vehicleType->id, $selectedVehicleTypes)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="vehicle_type_{{ $vehicleType->id }}">
                                                        {{ $vehicleType->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection