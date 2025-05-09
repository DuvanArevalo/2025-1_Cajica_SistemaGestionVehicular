<?php

namespace App\Models;

use App\Models\Role;
use App\Models\DocumentType;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo
use Illuminate\Database\Eloquent\SoftDeletes; // Importar SoftDeletes si lo usas (tu migración lo incluye)
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // implements MustVerifyEmail (si necesitas verificación de email)
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes; // Añadir SoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string> // Cambiado a array<int, string> para mejor compatibilidad
     */
    protected $fillable = [
        'name1',
        'name2',
        'lastname1',
        'lastname2',
        'email',
        'password',
        'role_id',
        'document_type_id',
        'document_number',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get yhe feture that owns the user.
     * @param string $featureName
     * @return bool
     */
    public function hasFeature($featureName)
    {
        // Obtiene todas las funcionalidades del rol del usuario
        return $this->role->features->contains('name', $featureName);
    }

    /**
     * Get the document type that owns the user.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    // Obtener nombre completo del usuario
    public function getFullNameAttribute(): string
    {
        return "{$this->name1} {$this->lastname1}";
    }
}