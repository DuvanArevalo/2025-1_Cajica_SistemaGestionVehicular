<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Obtener los tipos de vehículo asociados a esta sección.
     */
    public function vehicleTypes()
    {
        return $this->belongsToMany(VehicleType::class);
    }

    /**
     * Obtener las preguntas asociadas a esta sección.
     * Relación muchos a muchos usando la tabla pivote question_section.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }
}