@extends('layouts.app')

@section('title', 'Dashboard SST')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard SST') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Bienvenido al Panel de SST</h2>
                    <p>Desde aquí podrás gestionar todas las funciones relacionadas con Seguridad y Salud en el Trabajo.</p>
                    
                    <!-- Aquí puedes agregar widgets, estadísticas o accesos rápidos específicos para SST -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection