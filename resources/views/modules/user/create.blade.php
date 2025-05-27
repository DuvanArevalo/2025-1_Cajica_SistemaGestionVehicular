@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.users.index" 
            class="mb-3" 
            text="Volver al listado" 
        />
        
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Crear Nuevo Usuario</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route(Auth::user()->role->name . '.users.store') }}" autocomplete="off" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name1" class="form-label">Primer Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name1') is-invalid @enderror" id="name1" name="name1" value="{{ old('name1') }}" required>
                                    @error('name1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name2" class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control @error('name2') is-invalid @enderror" id="name2" name="name2" value="{{ old('name2') }}">
                                    @error('name2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lastname1" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('lastname1') is-invalid @enderror" id="lastname1" name="lastname1" value="{{ old('lastname1') }}" required>
                                    @error('lastname1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lastname2" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control @error('lastname2') is-invalid @enderror" id="lastname2" name="lastname2" value="{{ old('lastname2') }}">
                                    @error('lastname2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Rol <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                        <option value="">Seleccione un rol</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_type_id" class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                    <select class="form-select @error('document_type_id') is-invalid @enderror" id="document_type_id" name="document_type_id" required>
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($documentTypes as $docType)
                                            <option value="{{ $docType->id }}" {{ old('document_type_id') == $docType->id ? 'selected' : '' }}>
                                                {{ $docType->name }} ({{ $docType->abbreviation }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('document_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_number" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('document_number') is-invalid @enderror" id="document_number" name="document_number" autocomplete="off" value="" required>
                                    @error('document_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_active">¿Usuario Activo? (marque la casilla sí si esta activo)</label>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
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
