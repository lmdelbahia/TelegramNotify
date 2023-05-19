<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido'
    ];

    //Scopes

    //Helpers

    //Relaciones
    public function imagenes(): HasMany
    {
        return $this->hasMany(NoticiaImagen::class);
    }
}
