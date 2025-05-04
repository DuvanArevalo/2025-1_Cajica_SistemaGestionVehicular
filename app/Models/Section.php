<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    /**
     * The table associated with the model.
     *
     * Laravel infiere 'sections' por el nombre de la clase.
     * protected $table = 'sections';
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the questions for this section.
     * Una sección tiene muchas preguntas.
     */
    public function questions(): HasMany
    {
        // Asume 'section_id' como clave foránea en la tabla 'questions'
        return $this->hasMany(Question::class, 'section_id');
    }

    /**
     * Get the observations for this section.
     * Una sección puede tener muchas observaciones (en diferentes formularios).
     */
    public function observations(): HasMany
    {
        // Asume 'section_id' como clave foránea en la tabla 'observations'
        return $this->hasMany(Observation::class, 'section_id');
    }

    /**
     * The vehicle types that belong to the section.
     * Relación muchos a muchos con VehicleType a través de la tabla pivote.
     */
    public function vehicleTypes(): BelongsToMany
    {
        return $this->belongsToMany(VehicleType::class, 'section_vehicle_type');
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
    // No se necesitan casts específicos basados en la migración.
}