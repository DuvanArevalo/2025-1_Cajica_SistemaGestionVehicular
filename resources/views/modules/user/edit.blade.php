@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="mb-0">Editar Usuario</h2>
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

                    {{-- Formulario de edición --}}
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name1" class="form-label">Primer Nombre</label>
                                <input type="text" class="form-control" name="name1" value="{{ old('name1', $user->name1) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="name2" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" name="name2" value="{{ old('name2', $user->name2) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="lastname1" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control" name="lastname1" value="{{ old('lastname1', $user->lastname1) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="lastname2" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" name="lastname2" value="{{ old('lastname2', $user->lastname2) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">Rol</label>
                                <select name="role_id" class="form-select" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="is_active" class="form-label">¿Activo?</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
