@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('vehicle-types.index') }}" class="btn btn-light border border-dark text-dark px-4 py-2">
        ← Volver
    </a>
    <h1>Editar Tipo de Vehículo</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('vehicle-types.update', $vehicleType->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $vehicleType->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción:</label>
                    <textarea id="description" name="description" class="form-control" required>{{ $vehicleType->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning">Guardar Cambios ✏️</button>
            </form>
        </div>
    </div>
</div>
@endsection
