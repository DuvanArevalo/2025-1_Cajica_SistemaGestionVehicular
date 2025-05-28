<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar si defines relaciones

class Brand extends Model
{
    /**
     * The table associated with the model.
     *
     * Laravel infiere 'brands' por el nombre de la clase,
     * pero puedes especificarlo explícitamente si lo deseas.
     * protected $table = 'brands';
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];
    // Una marca puede tener muchos vehículos. 
    public function vehicles(): HasMany
    {
        // Asume una clave foránea 'brand_id' en la tabla 'vehicles'
        return $this->hasMany(Vehicle::class);
    }
    
    // Una marca puede tener muchos modelos de vehículos
    public function vehicleModels(): HasMany
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

    // No se necesitan casts específicos basados en la migración.
    // Timestamps (created_at, updated_at) son manejados automáticamente.
}
