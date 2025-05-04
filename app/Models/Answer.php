<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class Answer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'question_id',
        'value', // La respuesta booleana
    ];

    /**
     * The attributes that should be cast.
     *
     * Para asegurar que 'value' siempre sea tratado como booleano.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'boolean',
    ];

    /**
     * Get the preoperational form that owns the answer.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(PreoperationalForm::class, 'form_id');
    }

    /**
     * Get the question that this answer belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Get the alerts associated with this answer.
     * Una respuesta puede generar una o más alertas.
     */
    public function alerts(): HasMany
    {
        // Laravel asume 'answer_id' como clave foránea en la tabla 'alerts'
        return $this->hasMany(Alert::class);
    }
}