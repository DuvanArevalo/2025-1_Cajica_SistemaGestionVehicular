@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Usuarios</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return 
                    route="{{ Auth::user()->role->name }}.dashboard"
                    text="Volver al dashboard" 
                />

                <a href="{{ route(Auth::user()->role->name . '.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
                </a>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Usuarios</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.users.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="name" {{ request('filter_type') == 'name' ? 'selected' : '' }}>Nombre</option>
                                <option value="document" {{ request('filter_type') == 'document' ? 'selected' : '' }}>Documento</option>
                                <option value="email" {{ request('filter_type') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>
                        
                        <div id="name_filter" class="col-md-6 filter-field">
                            <label for="name_search" class="form-label">Buscar por nombre:</label>
                            <input type="text" class="form-control" id="name_search" name="name_search" value="{{ request('name_search') }}">
                        </div>
                        
                        <div id="document_filter" class="col-md-6 filter-field d-none">
                            <label for="document_search" class="form-label">Buscar por documento:</label>
                            <input type="text" class="form-control" id="document_search" name="document_search" value="{{ request('document_search') }}">
                        </div>
                        
                        <div id="email_filter" class="col-md-6 filter-field d-none">
                            <label for="email_search" class="form-label">Buscar por email:</label>
                            <input type="text" class="form-control" id="email_search" name="email_search" value="{{ request('email_search') }}">
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
                            <a href="{{ route(Auth::user()->role->name . '.users.index') }}" class="btn btn-secondary ms-2">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NOMBRE</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DOCUMENTO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">EMAIL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ROL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ESTADO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name1 }} {{ $user->lastname1 }}</td>
                                        <td>{{ $user->documentType->abbreviation }} {{ $user->document_number }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name }}</td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(Auth::user()->role->name . '.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                <a href="{{ route(Auth::user()->role->name . '.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </a>
                                                <form action="{{ route(Auth::user()->role->name . '.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                                        <i class="bi {{ $user->is_active ? 'bi-x-octagon' : 'bi-check2-circle' }}"></i>
                                                        {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No se encontraron usuarios</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() ?? 0 }} resultados
                            </div>
                            <div>
                                {{ $users->links() }}
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
        
        if (filterType === 'name') {
            document.getElementById('name_filter').classList.remove('d-none');
        } else if (filterType === 'document') {
            document.getElementById('document_filter').classList.remove('d-none');
        } else if (filterType === 'email') {
            document.getElementById('email_filter').classList.remove('d-none');
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
