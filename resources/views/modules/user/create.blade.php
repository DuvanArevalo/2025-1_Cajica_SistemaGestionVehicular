@extends('layouts.app')

@section('title', 'Registrar Usuario')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Botón fuera de la tarjeta --}}
            <div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.document-types.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

            {{-- Tarjeta principal --}}
            <div class="card shadow rounded-3 border-0">
            <div class="card-header text-center">
                <h2 class="mb-0">Crear nuevo usuario</h2>
            </div>

                <div class="card-body px-4 py-4">

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

                    {{-- Formulario --}}
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Primer Nombre</label>
                                <input type="text" class="form-control" name="name1" value="{{ old('name1') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" name="name2" value="{{ old('name2') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control" name="lastname1" value="{{ old('lastname1') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" name="lastname2" value="{{ old('lastname2') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rol</label>
                                <select name="role_id" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipo de Documento</label>
                                <select name="document_type_id" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($documentTypes as $docType)
                                        <option value="{{ $docType->id }}" {{ old('document_type_id') == $docType->id ? 'selected' : '' }}>
                                            {{ $docType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" name="document_number" value="{{ old('document_number') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estado</label>
                                <select name="is_active" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        {{-- Botón guardar --}}
                        <div class="mt-4 text-end">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-save me-1"></i> Guardar 
    </button>
</div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
