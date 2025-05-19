<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'answer_id',
        'observation_id',
        'alert_status_id',
    ];

    /**
     * Get the preoperational form that owns the alert.
     */
    public function preoperationalForm(): BelongsTo
    {
        return $this->belongsTo(PreoperationalForm::class, 'form_id');
    }

    /**
     * Get the answer that owns the alert.
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }

    /**
     * Get the observation that owns the alert.
     */
    public function observation(): BelongsTo
    {
        // Since observation_id is nullable, this relationship might return null
        return $this->belongsTo(Observation::class, 'observation_id');
    }

    /**
     * Get the status of the alert.
     */
    public function alertStatus(): BelongsTo
    {
        return $this->belongsTo(AlertStatus::class, 'alert_status_id');
    }
}