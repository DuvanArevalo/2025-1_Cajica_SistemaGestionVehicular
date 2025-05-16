@extends('layouts.app')

@section('title', 'Dashboard Conductor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
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