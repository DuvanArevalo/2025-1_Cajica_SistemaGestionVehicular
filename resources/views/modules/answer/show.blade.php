@extends('layouts.app')

@section('title', 'Detalles de la Respuesta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Fecha de Creación:</h6>
                                <p>{{ $answer->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Última Actualización:</h6>
                                <p>{{ $answer->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Formulario:</h6>
                                <p>Formulario #{{ $answer->form->id }} - {{ $answer->form->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Pregunta:</h6>
                                <p>{{ $answer->question->text }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Respuesta:</h6>
                                @if($answer->value === null)
                                    <span class="badge bg-secondary">No respondido</span>
                                @elseif($answer->value === 1)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route(Auth::user()->role->name . '.answers.edit', $answer->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection