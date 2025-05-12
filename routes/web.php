<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers generales
use App\Http\Controllers\Role\AdminController as AdminController;
use App\Http\Controllers\Role\SSTController as SSTController;
use App\Http\Controllers\Role\ConductorController as ConductorController;

// Controllers modulos - CRUD
use App\Http\Controllers\Modules\AlertController as AlertController;
use App\Http\Controllers\Modules\AlertStatusController as AlertStatusController;
use App\Http\Controllers\Modules\AnswerController as AnswerController;
use App\Http\Controllers\Modules\DocumentTypeController as DocumentTypeController;
use App\Http\Controllers\Modules\ObservationController as ObservationController;
use App\Http\Controllers\Modules\PreoperationalFormController as PreoperationalFormController;
use App\Http\Controllers\Modules\QuestionController as QuestionController;
use App\Http\Controllers\Modules\RoleController as RoleController;
use App\Http\Controllers\Modules\SectionController as SectionController;
use App\Http\Controllers\Modules\UserController as UserController;
use App\Http\Controllers\Modules\VehicleBrandController as VehicleBrandController;
use App\Http\Controllers\Modules\VehicleController as VehicleController;
use App\Http\Controllers\Modules\VehicleModelController as VehicleModelController;
use App\Http\Controllers\Modules\VehicleTypeController as VehicleTypeController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function() {
    if (Auth::check()) {
        $roleName = strtolower(Auth::user()->role->name);
        return match ($roleName) {
            'admin'     => redirect()->route('admin.dashboard'),
            'sst'       => redirect()->route('sst.dashboard'),
            'conductor' => redirect()->route('conductor.dashboard'),
            default     => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
})->name('dashboard');

Auth::routes();

// Grupo para rutas de Administrador
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Modulos
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::resource('document-types', DocumentTypeController::class)->except(['destroy']);
    Route::resource('roles', RoleController::class)->except(['destroy']);
    Route::resource('vehicles', VehicleController::class)->except(['destroy']);
    Route::resource('vehicle-brands', VehicleBrandController::class)->except(['destroy']);
    Route::resource('vehicle-models', VehicleModelController::class)->except(['destroy']);
    Route::resource('vehicle-types', VehicleTypeController::class)->except(['destroy']);
    Route::resource('preoperational-forms', PreoperationalFormController::class)->except(['destroy']);
    Route::resource('sections', SectionController::class)->except(['destroy']);
    Route::resource('questions', QuestionController::class)->except(['destroy']);
    Route::resource('alerts', AlertController::class)->except(['destroy']);
    Route::resource('alert-statuses', AlertStatusController::class)->except(['destroy']);
    Route::resource('observations', ObservationController::class)->except(['destroy']);
});

// Grupo para rutas de SST
Route::middleware(['auth', 'role:sst'])->prefix('sst')->name('sst.')->group(function () {
    Route::get('/dashboard', [SSTController::class, 'index'])->name('dashboard');
    
    // Modulos específicos para SST
    Route::resource('users', UserController::class)->except(['destroy'])->middleware('restrict.admin.sst');
    Route::resource('document-types', DocumentTypeController::class)->except(['destroy']);
    Route::resource('roles', RoleController::class)->except(['destroy'])->middleware('restrict.admin.sst');
    Route::resource('vehicles', VehicleController::class)->except(['destroy']);
    Route::resource('vehicle-brands', VehicleBrandController::class)->except(['destroy']);
    Route::resource('vehicle-models', VehicleModelController::class)->except(['destroy']);
    Route::resource('vehicle-types', VehicleTypeController::class)->except(['destroy']);
    Route::resource('preoperational-forms', PreoperationalFormController::class)->except(['destroy']);
    Route::resource('sections', SectionController::class)->except(['destroy']);
    Route::resource('questions', QuestionController::class)->except(['destroy']);
    Route::resource('alerts', AlertController::class)->except(['destroy']);
    Route::resource('alert-statuses', AlertStatusController::class)->only(['index','show']);
    Route::resource('observations', ObservationController::class)->except(['destroy']);
});

// Grupo para rutas de Conductor
Route::middleware(['auth', 'role:conductor'])->prefix('conductor')->name('conductor.')->group(function () {
    Route::get('/dashboard', [ConductorController::class, 'index'])->name('dashboard');
    
    // Modulos específicos para Conductor - Acceso limitado
    Route::resource('vehicles', VehicleController::class)->only(['index']);
    Route::resource('preoperational-forms', PreoperationalFormController::class)->only(['index', 'show', 'create', 'store']);
    Route::resource('answers', AnswerController::class)->only(['index', 'show']);
    Route::resource('observations', ObservationController::class)->only(['index', 'show']);
});
