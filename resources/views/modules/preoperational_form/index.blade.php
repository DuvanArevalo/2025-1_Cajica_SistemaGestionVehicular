@extends('layouts.app')

@section('title', 'Formularios Preoperacionales')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Formularios Preoperacionales</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return 
                    route="{{ Auth::user()->role->name }}.dashboard"
                    text="Volver al dashboard" 
                />

                @if(strtolower(Auth::user()->role->name) != 'conductor')
                <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Formulario
                </a>
                @endif
            </div>
        </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Formularios</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.preoperational-forms.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                @if(strtolower(Auth::user()->role->name) != 'conductor')
                                <option value="user" {{ request('filter_type') == 'user' ? 'selected' : '' }}>Conductor</option>
                                @endif
                                <option value="vehicle" {{ request('filter_type') == 'vehicle' ? 'selected' : '' }}>Vehículo</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de fechas</option>
                            </select>
                        </div>
                        
                        <!-- Resto de los filtros -->
                        <div id="user_filter" class="col-md-6 filter-field">
                            <label for="user_search" class="form-label">Buscar por conductor:</label>
                            <input type="text" class="form-control" id="user_search" name="user_search" value="{{ request('user_search') }}">
                        </div>
                        
                        <div id="vehicle_filter" class="col-md-6 filter-field d-none">
                            <label for="vehicle_search" class="form-label">Buscar por vehículo (placa, marca, modelo o tipo):</label>
                            <input type="text" class="form-control" id="vehicle_search" name="vehicle_search" value="{{ request('vehicle_search') }}" placeholder="Ingrese placa, marca, modelo o tipo de vehículo">
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
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Atención:</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Conductor</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehículo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kilometraje</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($preoperationalForms as $form)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $form->id }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $form->user ? $form->user->fullName : 'Usuario no disponible' }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $form->vehicle->plate }} - {{ $form->vehicle->brand->name }} {{ $form->vehicle->model->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ number_format($form->new_mileage, 0, ',', '.') }} km</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $form->created_at->format('d/m/Y H:i') }}</p>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.show', $form->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                @if(strtolower(Auth::user()->role->name) != 'conductor')
                                                <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.edit', $form->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No se encontraron formularios preoperacionales</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $preoperationalForms->firstItem() }} a {{ $preoperationalForms->lastItem() }} de {{ $preoperationalForms->total() }} resultados
                            </div>
                            <div>
                                {{ $preoperationalForms->links() }}
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
        
        if (filterType === 'user') {
            document.getElementById('user_filter').classList.remove('d-none');
        } else if (filterType === 'vehicle') {
            document.getElementById('vehicle_filter').classList.remove('d-none');
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