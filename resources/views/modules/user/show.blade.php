@extends('layouts.app')

@section('title', 'Detalle de usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <x-partial.bs-return />

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Información del usuario</h4>
                </div>

                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nombre completo</dt>
                        <dd class="col-sm-8">
                            {{ $user->name1 }} {{ $user->name2 }} {{ $user->lastname1 }} {{ $user->lastname2 }}
                        </dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Rol</dt>
                        <dd class="col-sm-8">{{ $user->role->name ?? 'Sin rol' }}</dd>

                        <dt class="col-sm-4">Documento</dt>
                        <dd class="col-sm-8">
                            {{ $user->documentType->name ?? '' }} – {{ $user->document_number }}
                        </dd>

                        <dt class="col-sm-4">Activo</dt>
                        <dd class="col-sm-8">{{ $user->is_active ? 'Sí' : 'No' }}</dd>

                        <dt class="col-sm-4">Creado</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
