@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Botón para registrar nueva marca --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ $dashboardRoute }}" class="btn btn-secondary mb-3">← Volver</a>
        <h1>Marcas de Vehículos</h1>
        <a href="{{ route('brands.create') }}" class="btn btn-primary">Registrar Nueva Marca</a>
    </div>

    {{-- Tabla con lista de marcas --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr>
                            <td>{{ $brand->name }}</td>
                            <td>
                                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar ❌</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No hay marcas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
