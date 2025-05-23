@extends('layouts.app')

@section('content')
<div class="container py-4">
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
                    <div class="col-md-12">
                        <label for="image">Imagen del Vehículo</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="vehicle_type_id">Tipo de vehículo</label>
                        <input type="number" name="vehicle_type_id" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="brand_id">Marca</label>
                        <input type="number" name="brand_id" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="model_id">Modelo</label>
                        <input type="number" name="model_id" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="model_year">Año del modelo</label>
                        <input type="text" name="model_year" maxlength="4" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="wheel_count">Número de ruedas</label>
                        <input type="text" name="wheel_count" maxlength="2" class="form-control" required>
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
