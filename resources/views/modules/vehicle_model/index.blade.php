@extends('layouts.app')

@section('title', 'Modelos de Vehículo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Modelos de Vehículo</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return
                    route="{{ Auth::user()->role->name }}.dashboard"
                    text="Volver al dashboard" />

                <a href="{{ route(Auth::user()->role->name .'.vehicle-models.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Modelo de Vehículo
                </a>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Filtrar Modelos de Vehículo</h5></div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name .'.vehicle-models.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="name" {{ request('filter_type') == 'name' ? 'selected' : '' }}>Nombre</option>
                                <option value="brand" {{ request('filter_type') == 'brand' ? 'selected' : '' }}>Marca</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>

                        <div id="name_filter" class="col-md-6 filter-field">
                            <label for="name_search" class="form-label">Buscar por nombre:</label>
                            <input type="text" class="form-control" id="name_search" name="name_search" value="{{ request('name_search') }}">
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

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <a href="{{ route(Auth::user()->role->name .'.vehicle-models.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabla de resultados --}}
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MARCA</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MODELO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicleModels as $model)
                                    <tr>
                                        <td>{{ $model->brand->name }}</td>
                                        <td>{{ $model->name }}</td>
                                        <td>{{ $model->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(Auth::user()->role->name .'.vehicle-models.show', $model->id) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                                <a href="{{ route(Auth::user()->role->name .'.vehicle-models.edit', $model->id) }}"
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No se encontraron modelos de vehículo</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center mt-4 mx-4">
                            <div>
                                Mostrando {{ $vehicleModels->firstItem() ?? 0 }}
                                a {{ $vehicleModels->lastItem() ?? 0 }}
                                de {{ $vehicleModels->total() ?? 0 }} resultados
                            </div>
                            <div>
                                {{ $vehicleModels->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script para mostrar/ocultar filtros --}}
<script>
    function toggleFilterFields() {
        document.querySelectorAll('.filter-field').forEach(f => f.classList.add('d-none'));
        const type = document.getElementById('filter_type').value;
        if (type === 'name') {
            document.getElementById('name_filter').classList.remove('d-none');
        } else if (type === 'brand') {
            document.getElementById('brand_filter').classList.remove('d-none');
        } else if (type === 'date_range') {
            document.getElementById('date_range_filter').classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', toggleFilterFields);
</script>
@endsection
