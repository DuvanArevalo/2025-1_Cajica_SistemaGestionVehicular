<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class AlertStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'description',
    ];

    /**
     * Get the alerts associated with the alert status.
     * Un estado de alerta puede tener muchas alertas.
     */
    public function alerts(): HasMany
    {
        // Laravel asume 'alert_status_id' como clave foránea en la tabla 'alerts'
        return $this->hasMany(Alert::class);
    }
}