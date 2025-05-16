@extends('layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('catalogo.index') }}" class="btn btn-light border border-dark text-dark px-4 py-2">
        ← Volver
    </a>
    <h2 class="mb-4 text-center">Registrar Vehículo</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-lg rounded">
        <div class="card-body">
            <form action="{{ route('vehiculos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                            <label for="vehicle-types_id" class="form-label">Tipo de Vehículo:</label>
                            <select id="vehicle-types_id" name="vehicle-types_id" class="form-control" required>
                        @foreach($vehicleTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                            </select>
                    </div>
                    <div class="col-md-6">
                    <label for="brand_id" class="form-label">Marca:</label>
                    <select id="brand_id" name="brand_id" class="form-control" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                                <label for="vehicle-models_id" class="form-label">Modelo:</label>
                                <select id="vehicle-models_id" name="vehicle-models_id" class="form-control" required>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="model_year">Año del modelo</label>
                        <input type="text" name="model_year" maxlength="4" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="wheel_count">Número de ruedas</label>
                        <input type="number" name="wheel_count" maxlength="2" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="color">Color</label>
                        <input type="text" name="color" maxlength="50" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="plate">Placa</label>
                        <input type="text" name="plate" maxlength="6" class="form-control" required>
                        @if ($errors->has('plate'))
                            <span class="text-danger">{{ $errors->first('plate') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="mileage">Kilometraje</label>
                        <input type="number" name="mileage" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="is_active">¿Activo?</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="soat">Fecha SOAT</label>
                        <input type="date" name="soat" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="soat_status">Estado del SOAT</label>
                        <select name="soat_status" class="form-control" required>
                            <option value="1">Vigente</option>
                            <option value="0">Vencido</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="mechanical_review">Fecha revisión mecánica</label>
                        <input type="date" name="mechanical_review" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="mechanical_review_status">Estado revisión mecánica</label>
                        <select name="mechanical_review_status" class="form-control" required>
                            <option value="1">Aprobado</option>
                            <option value="0">Reprobado</option>
                        </select>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-5">Registrar Vehículo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
