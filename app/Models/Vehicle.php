<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /**
     * The table associated with the model.
     *
     * Laravel infiere 'vehicles' por el nombre de la clase.
     * protected $table = 'vehicles';
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_type_id',
        'brand_id',
        'model_id',
        'model_year',
        'wheel_count',
        'color',
        'plate',
        'mileage',
        'is_active',
        'soat',
        'soat_status',
        'mechanical_review',
        'mechanical_review_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mileage' => 'integer',
        'is_active' => 'boolean',
        'soat' => 'date',                   // fecha del soat
        'soat_status' => 'boolean',
        'mechanical_review' => 'date',      // fecha de la revisión tecnomecanica
        'mechanical_review_status' => 'boolean',
    ];

    /**
     * Obtener el tipo de vehículo.
     */
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    /**
     * Obtener la marca del vehículo.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Obtener el modelo del vehículo.
     */
    public function model(): BelongsTo
    {
        // Asume que el modelo se llama VehicleModel y la clave foránea es 'model_id'
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    /**
     * Get the preoperational forms for the vehicle.
     * Un vehículo puede tener muchos formularios preoperacionales.
     */
    public function preoperationalForms(): HasMany
    {
        return $this->hasMany(PreoperationalForm::class, 'vehicle_id');
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
}