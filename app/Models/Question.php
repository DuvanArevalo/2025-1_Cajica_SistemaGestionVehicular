<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
    ];

    /**
     * Obtener las secciones asociadas a esta pregunta.
     * Relación muchos a muchos usando la tabla pivote question_section.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'question_section');
    }

    /**
     * Get the answers for this question.
     * Una pregunta puede tener muchas respuestas (en diferentes formularios).
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    // Timestamps (created_at, updated_at) son manejados automáticamente.
    // No se necesitan casts específicos basados en la migración.
}