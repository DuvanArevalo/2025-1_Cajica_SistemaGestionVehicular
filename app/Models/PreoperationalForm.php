<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreoperationalForm extends Model
{
    /**
     * The table associated with the model.
     *
     * Laravel infiere 'preoperational_forms' por el nombre de la clase.
     * protected $table = 'preoperational_forms';
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'new_mileage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'new_mileage' => 'integer',
    ];

    /**
     * Un formulario le pertenece a un vehículo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Un formulario le pertenece a un usuario (conductor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the answers associated with this form.
     * Un formulario tiene muchas respuestas.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'form_id');
    }

    /**
     * Get the observations associated with this form.
     * Un formulario puede tener muchas observaciones.
     */
    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class, 'form_id');
    }

    /**
     * Get the alerts generated from this form.
     * Un formulario puede generar muchas alertas.
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class, 'form_id');
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
}