<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\FieldsOptions\RoleFieldOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Scopes
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('role', RoleFieldOptions::ADMIN->value);
    }

    public function scopeClient(Builder $query): Builder
    {
        return $query->where('role', RoleFieldOptions::CLIENT->value);
    }

    //Helpers
    public function isAdmin(): bool
    {
        return $this->role == RoleFieldOptions::ADMIN->value;
    }

    public function isClient(): bool
    {
        return $this->role == RoleFieldOptions::CLIENT->value;
    }

    //Relaciones
    public function bots(): HasMany
    {
        return $this->hasMany(Bot::class);
    }

    public function noticias(): HasMany
    {
        return $this->hasMany(Noticia::class);
    }
}
