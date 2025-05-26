@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        @if(isset($dashboardRoute))
            <a href="{{ $dashboardRoute }}" class="btn btn-secondary mb-3">← Volver</a>
        @endif
        <h1>Modelos de Vehículos</h1>
        <a href="{{ route('vehicle-models.create') }}" class="btn btn-primary">Registrar Nuevo Modelo</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicleModels as $model)
                        <tr>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->brand->name }}</td>
                            <td>
                                <a href="{{ route('vehicle-models.edit', $model->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('vehicle-models.destroy', $model->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar ❌</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection