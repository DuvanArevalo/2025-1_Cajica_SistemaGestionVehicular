@extends('layouts.app')

@section('title', 'Dashboard Conductor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Conductor') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Bienvenido al Panel de Conductor</h2>
                    <p>Desde aquí podrás gestionar todas tus actividades como conductor y realizar los formularios preoperacionales.</p>
                    
                    <!-- Aquí puedes agregar widgets, estadísticas o accesos rápidos específicos para conductores -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection