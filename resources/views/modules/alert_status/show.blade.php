@extends('layouts.app')

@section('title', 'Detalle de Estado de Alerta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.alert-statuses.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Fecha de Creación:</h6>
                                <p>{{ $alertStatus->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Nombre:</h6>
                                <p>{{ $alertStatus->type }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                                <p>{{ $alertStatus->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Descripción:</h6>
                                <p>{{ $alertStatus->description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.alert-statuses.edit', $alertStatus->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection