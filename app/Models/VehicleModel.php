<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleModel extends Model
{
    /**
     * The table associated with the model.
     *
     * Es necesario especificarlo porque 'vehicles_models' no es la pluralización estándar de 'VehicleModel'.
     * @var string
     */
    protected $table = 'vehicles_models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'brand_id',
    ];

    /**
     * Get the brand that owns the model.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the vehicles for the model.
     * Un modelo puede estar asociado a muchos vehículos.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'model_id');
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
    // No se necesitan casts específicos basados en la migración.
}