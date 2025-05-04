<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Observation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'section_id',
        'text',
    ];

    /**
     * Get the preoperational form that owns the observation.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(PreoperationalForm::class, 'form_id');
    }

    /**
     * Get the section that owns the observation.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get the alerts associated with this observation.
     * Una observación puede estar asociada con una o más alertas.
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
    // No se necesitan casts específicos basados en la migración.
}