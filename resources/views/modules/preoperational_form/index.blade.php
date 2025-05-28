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
                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
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
                                <option value="user" {{ request('filter_type') == 'user' ? 'selected' : '' }}>Conductor</option>
                                <option value="vehicle" {{ request('filter_type') == 'vehicle' ? 'selected' : '' }}>Vehículo</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>
                        
                        <div id="user_filter" class="col-md-6 filter-field">
                            <label for="user_search" class="form-label">Buscar por conductor:</label>
                            <input type="text" class="form-control" id="user_search" name="user_search" value="{{ request('user_search') }}">
                        </div>
                        
                        <div id="vehicle_filter" class="col-md-6 filter-field d-none">
                            <label for="vehicle_search" class="form-label">Buscar por vehículo:</label>
                            <input type="text" class="form-control" id="vehicle_search" name="vehicle_search" value="{{ request('vehicle_search') }}">
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
                            <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </a>
                            @if(in_array(strtolower(Auth::user()->role->name), ['admin','sst']))
                            <a href="{{ route(Auth::user()->role->name.'.preoperational-forms.export', request()->all()) }}" class="btn btn-danger ms-2">
                                <i class="bi bi-file-earmark-pdf-fill"></i> Exportar PDF
                            </a>
                            @endif
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CONDUCTOR</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VEHÍCULO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">KILOMETRAJE</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($preoperationalForms as $form)
                                    <tr>
                                        <td>{{ $form->id }}</td>
                                        <td>{{ $form->user->fullName }}</td>
                                        <td>{{ $form->vehicle->plate }} - {{ $form->vehicle->brand->name }} - {{ $form->vehicle->model->name }}</td>
                                        <td>{{ $form->new_mileage }}</td>
                                        <td>{{ $form->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.show', $form->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
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