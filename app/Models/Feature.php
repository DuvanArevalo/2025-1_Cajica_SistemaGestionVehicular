<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];
    
    // Las funcionalidades se asignan a roles
     public function roles(): BelongsToMany
     {
        // Asume una tabla pivote 'role_feature' con 'feature_id' y 'role_id'
        return $this->belongsToMany(Role::class);
     }

    // No se necesitan casts específicos basados en la migración.
    // Timestamps (created_at, updated_at) son manejados automáticamente.
}