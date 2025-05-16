@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Botón para registrar nuevo tipo --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ $dashboardRoute }}" class="btn btn-secondary mb-3">← Volver</a>
        <h1>Tipos de Vehículos</h1>
        <a href="{{ route('vehicle-types.create') }}" class="btn btn-primary">Registrar Tipo de Vehículo</a>
    </div>

    {{-- Tabla con lista de tipos de vehículos --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicleTypes as $type)
                        <tr>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->description }}</td>
                            <td>
                                    <a href="{{ route('vehicle-types.edit', $type->id) }}" class="btn btn-warning btn-sm">Editar</a>
    
                                        <form action="{{ route('vehicle-types.destroy', $type->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este tipo de vehículo?')">Eliminar ❌</button>
                                    </form>
                                </td>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay tipos de vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
