@extends('layouts.app')

@section('title', 'Vehículos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Vehículos</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return
                    route="{{ Auth::user()->role->name }}.dashboard"
                    text="Volver al dashboard" />

                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                <a href="{{ route(Auth::user()->role->name .'.vehicles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Vehículo
                </a>
                @endif
            </div>
        </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Vehículos</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name .'.vehicles.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="plate" {{ request('filter_type') == 'plate' ? 'selected' : '' }}>Placa</option>
                                <option value="type" {{ request('filter_type') == 'type' ? 'selected' : '' }}>Tipo</option>
                                <option value="brand" {{ request('filter_type') == 'brand' ? 'selected' : '' }}>Marca</option>
                                <option value="model" {{ request('filter_type') == 'model' ? 'selected' : '' }}>Modelo</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>

                        <div id="plate_filter" class="col-md-6 filter-field">
                            <label for="plate_search" class="form-label">Buscar por placa:</label>
                            <input type="text" class="form-control" id="plate_search" name="plate_search" value="{{ request('plate_search') }}">
                        </div>

                        <div id="type_filter" class="col-md-6 filter-field d-none">
                            <label for="vehicle_type_id" class="form-label">Seleccionar tipo:</label>
                            <select class="form-select" id="vehicle_type_id" name="vehicle_type_id">
                                <option value="">Seleccione un tipo</option>
                                @foreach($vehicleTypes as $type)
                                <option value="{{ $type->id }}" {{ request('vehicle_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="brand_filter" class="col-md-6 filter-field d-none">
                            <label for="brand_id" class="form-label">Seleccionar marca:</label>
                            <select class="form-select" id="brand_id" name="brand_id">
                                <option value="">Seleccione una marca</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="model_filter" class="col-md-6 filter-field d-none">
                            <label for="model_id" class="form-label">Seleccionar modelo:</label>
                            <select class="form-select" id="model_id" name="model_id">
                                <option value="">Seleccione un modelo</option>
                                @foreach($models as $model)
                                <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                                    {{ $model->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="date_range_filter" class="col-md-6 filter-field d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_from" class="form-label">Fecha inicio:</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to" class="form-label">Fecha fin:</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="is_active" class="form-label">Estado:</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="">Todos</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <a href="{{ route(Auth::user()->role->name .'.vehicles.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PLACA</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TIPO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MARCA</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MODELO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">AÑO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ESTADO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->plate }}</td>
                                    <td>{{ $vehicle->vehicleType->name }}</td>
                                    <td>{{ $vehicle->brand->name }}</td>
                                    <td>{{ $vehicle->model->name }}</td>
                                    <td>{{ $vehicle->model_year }}</td>
                                    <td>
                                        <span class="badge bg-{{ $vehicle->is_active ? 'success' : 'danger' }}">
                                            {{ $vehicle->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route(Auth::user()->role->name .'.vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                                Ver
                                            </a>
                                            @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                                            <a href="{{ route(Auth::user()->role->name .'.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                                Editar
                                            </a>
                                            <form action="{{ route(Auth::user()->role->name .'.vehicles.toggle-status', $vehicle->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $vehicle->is_active ? 'danger' : 'success' }}">
                                                    <i class="bi bi-toggle-{{ $vehicle->is_active ? 'on' : 'off' }}"></i>
                                                    {{ $vehicle->is_active ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                            @endif
                                            <a href="{{ route(Auth::user()->role->name .'.preoperational-forms.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-file-earmark-plus"></i>
                                                Formulario
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No se encontraron vehículos</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $vehicles->firstItem() ?? 0 }} a {{ $vehicles->lastItem() ?? 0 }} de {{ $vehicles->total() ?? 0 }} resultados
                            </div>
                            <div>
                                {{ $vehicles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFilterFields() {
        // Ocultar todos los campos de filtro
        document.querySelectorAll('.filter-field').forEach(field => {
            field.classList.add('d-none');
        });

        // Mostrar el campo correspondiente al filtro seleccionado
        const filterType = document.getElementById('filter_type').value;

        if (filterType === 'plate') {
            document.getElementById('plate_filter').classList.remove('d-none');
        } else if (filterType === 'type') {
            document.getElementById('type_filter').classList.remove('d-none');
        } else if (filterType === 'brand') {
            document.getElementById('brand_filter').classList.remove('d-none');
        } else if (filterType === 'model') {
            document.getElementById('model_filter').classList.remove('d-none');
        } else if (filterType === 'date_range') {
            document.getElementById('date_range_filter').classList.remove('d-none');
        }
    }

    // Ejecutar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilterFields();
    });
</script>
@endsection