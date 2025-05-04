<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleType extends Model
{
    /**
     * The table associated with the model.
     *
     * Laravel infiere 'vehicle_types' por el nombre de la clase.
     * protected $table = 'vehicle_types';
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the vehicles for the vehicle type.
     * Un tipo de vehículo puede tener muchos vehículos.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'vehicle_type_id');
    }

    /**
     * The sections that belong to the vehicle type.
     * Relación muchos a muchos con Section a través de la tabla pivote.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'section_vehicle_type');
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
    // No se necesitan casts específicos basados en la migración.
}
