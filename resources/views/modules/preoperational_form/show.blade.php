@extends('layouts.app')

@section('title', 'Detalle del Formulario Preoperacional')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.preoperational-forms.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Información del Formulario</h6>
                </div>
                <div class="card-body">
                    {{-- Datos generales --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> {{ $preoperationalForm->id }}</p>
                            <p><strong>Usuario:</strong> {{ $preoperationalForm->user->fullName }}</p>
                            <p><strong>Vehículo:</strong> {{ $preoperationalForm->vehicle->plate }} – {{ $preoperationalForm->vehicle->brand->name }} {{ $preoperationalForm->vehicle->model->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Kilometraje registrado:</strong> {{ number_format($preoperationalForm->new_mileage, 0, ',', '.') }} km</p>
                            <p><strong>Creado:</strong> {{ $preoperationalForm->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Actualizado:</strong> {{ $preoperationalForm->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- Secciones, preguntas y respuestas --}}
                    <h6 class="mb-3">Respuestas y Observaciones</h6>

                    @foreach($sections as $section)
                        <div class="card mb-3 section-card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">{{ $section->name }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Pregunta</th>
                                                <th width="120">Respuesta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($section->questions as $question)
                                                @php
                                                    $answer = $preoperationalForm->answers->firstWhere('question_id', $question->id);
                                                @endphp
                                                <tr>
                                                    <td>{{ $question->text }}</td>
                                                    <td>
                                                        @if(is_null($answer))
                                                            <span class="badge bg-secondary">Sin respuesta</span>
                                                        @elseif($answer->value == 1)
                                                            <span class="badge bg-success">Sí</span>
                                                        @else
                                                            <span class="badge bg-danger">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Observaciones --}}
                                @php
                                    $obs = $preoperationalForm->observations->firstWhere('section_id', $section->id);
                                @endphp
                                <div>
                                    <h6>Observaciones:</h6>
                                    <div class="p-3 bg-light rounded">
                                        {{ $obs ? $obs->text : 'Sin observaciones' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Botones --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route(Auth::user()->role->name . '.preoperational-forms.edit', $preoperationalForm->id) }}"
                        class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i>Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
