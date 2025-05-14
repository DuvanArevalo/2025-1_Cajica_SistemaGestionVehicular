@extends('layouts.app')

@section('title', 'Editar Pregunta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Editar Pregunta</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.questions.update', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Secciones</label>
                            
                            @error('sections')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            
                            <div class="border p-3 rounded">
                                @foreach($sections as $section)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" 
                                        name="sections[]" 
                                        id="section_{{ $section->id }}" 
                                        value="{{ $section->id }}"
                                        {{ (is_array(old('sections')) && in_array($section->id, old('sections'))) || 
                                           (old('sections') === null && in_array($section->id, $selectedSections)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="section_{{ $section->id }}">
                                            {{ $section->name }}
                                            @if($section->vehicleTypes->count() > 0)
                                                ({{ $section->vehicleTypes->pluck('name')->implode(', ') }})
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="text" class="form-label">Texto de la Pregunta</label>
                            <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text" rows="3" required>{{ old('text', $question->text) }}</textarea>
                            @error('text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection