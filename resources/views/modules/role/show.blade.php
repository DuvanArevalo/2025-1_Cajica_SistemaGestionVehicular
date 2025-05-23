@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle Rol</h1>
    <p><strong>Nombre:</strong> {{ $role->name }}</p>
    <p><strong>Descripción:</strong> {{ $role->description }}</p>
    <p><strong>Activo:</strong> {{ $role->is_active ? 'Sí' : 'No' }}</p>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection