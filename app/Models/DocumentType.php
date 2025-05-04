<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importar HasFactory si usas factories
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class DocumentType extends Model
{
    use HasFactory; // Usar HasFactory si tienes factories para este modelo

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_types'; // Especificar el nombre de la tabla si no sigue la convención

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'abbreviation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    /**
     * Get the users associated with the document type.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}