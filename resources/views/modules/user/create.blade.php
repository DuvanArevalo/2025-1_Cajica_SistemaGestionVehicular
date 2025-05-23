@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="mb-0">Registrar Nuevo Usuario</h2>
                </div>
                <div class="card-body">
                    {{-- Validación de errores --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario de creación --}}
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name1" class="form-label">Primer Nombre</label>
                                <input type="text" class="form-control" name="name1" value="{{ old('name1') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="name2" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" name="name2" value="{{ old('name2') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="lastname1" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control" name="lastname1" value="{{ old('lastname1') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="lastname2" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" name="lastname2" value="{{ old('lastname2') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label for="role_id" class="form-label">Rol</label>
                                <select name="role_id" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="document_type_id" class="form-label">Tipo de Documento</label>
                                <select name="document_type_id" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($documentTypes as $docType)
                                        <option value="{{ $docType->id }}" {{ old('document_type_id') == $docType->id ? 'selected' : '' }}>
                                            {{ $docType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="document_number" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" name="document_number" value="{{ old('document_number') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="is_active" class="form-label">Estado</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle me-1"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
