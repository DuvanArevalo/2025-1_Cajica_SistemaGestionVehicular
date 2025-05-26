@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('vehicle-models.index') }}" class="btn btn-light border border-dark text-dark px-4 py-2">
        ← Volver
    </a>
    <h1>Registrar Nuevo Modelo</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('vehicle-models.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="brand_id" class="form-label">Marca:</label>
                    <select id="brand_id" name="brand_id" class="form-control" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection