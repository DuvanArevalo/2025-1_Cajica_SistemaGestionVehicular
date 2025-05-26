@extends('layouts.app')

@section('content')
<div class="container">

    

    {{-- Título y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        @if(Auth::check() && Auth::user()->role)
    @php
        $role = strtolower(Auth::user()->role->name);
        $dashboardRoute = match($role) {
            'admin' => route('admin.dashboard'),
            'sst' => route('sst.dashboard'),
            'conductor' => route('conductor.dashboard'),
            default => route('home')
        };
    @endphp

    <a href="{{ $dashboardRoute }}" class="btn btn-secondary mb-3">← Volver</a>
@endif
        <h1 class="text-center flex-grow-1">Listado de Vehículos</h1>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">Registrar Vehículo</a>
    </div>

{{-- Sección de filtrado con diseño mejorado --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-3">Filtrar Vehículos</h5>
        <form method="GET" action="{{ route('catalogo.index') }}">
            <div class="row g-3">
                {{-- Selector de filtro --}}
                <div class="col-md-4">
                    <select id="filterType" class="form-control">
                        <option value="">Seleccione filtro...</option>
                        <option value="marca">Marca</option>
                        <option value="modelo">Modelo</option>
                        <option value="placa">Placa</option>
                        <option value="tipo">Tipo</option>
                        <option value="año">Año</option>
                        <option value="color">Color</option>
                    </select>
                </div>

                {{-- Campo de búsqueda dinámico --}}
                <div class="col-md-4">
                    <input type="text" id="filterInput" name="" class="form-control" placeholder="Ingrese valor">
                </div>

                {{-- Botón de búsqueda --}}
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark px-4">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('filterType').addEventListener('change', function() {
        let selectedFilter = this.value;
        let inputField = document.getElementById('filterInput');

        // Ajusta el name del input según el filtro seleccionado
        inputField.name = selectedFilter;
        inputField.placeholder = "Ingrese " + selectedFilter;
    });
</script>


    {{-- Tabla de vehículos --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Placa</th>
                        <th>Kilometraje</th>
                        <th>SOAT</th>
                        <th>Tecnomecánica</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->brand->name ?? 'Desconocida' }}</td>
                            <td>{{ $vehicle->model->name ?? 'Desconocido' }}</td>
                            <td>{{ $vehicle->vehicleType->name ?? 'No especificado' }}</td>
                            <td>{{ $vehicle->model_year }}</td>
                            <td>{{ $vehicle->color }}</td>
                            <td>{{ $vehicle->plate }}</td>
                            <td>{{ number_format($vehicle->mileage) }} km</td>
                            <td>
                                {{ $vehicle->soat }}
                                <span class="badge {{ $vehicle->soat_status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $vehicle->soat_status ? 'Vigente' : 'Vencido' }}
                                </span>
                            </td>
                            <td>
                                {{ $vehicle->mechanical_review }}
                                <span class="badge {{ $vehicle->mechanical_review_status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $vehicle->mechanical_review_status ? 'Aprobada' : 'Vencida' }}
                                </span>
                            </td>
                            
                               <td class="text-center">
                                    <a href="{{ route('vehiculos.show', $vehicle->id) }}" class="btn btn-info btn-sm">Ver</a>

                                @if(Auth::check() && in_array(Auth::user()->role->name, ['admin', 'sst']))
                                    <a href="{{ route('vehiculos.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                @endif
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection