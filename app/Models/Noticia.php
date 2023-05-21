<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'contenido'
    ];

    //Scopes

    //Helpers

    //Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(NoticiaImagen::class);
    }

    public function botDestinations(): BelongsToMany
    {
        return $this->belongsToMany(BotDestination::class);
    }
}
