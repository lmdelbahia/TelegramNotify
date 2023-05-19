<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoticiaImagen extends Model
{
    use HasFactory;

    protected $fillable = [
        'noticia_id',
        'path',
        'descripcion'
    ];

    //Scopes

    //Helpers

    //Relaciones
    public function noticia(): BelongsTo
    {
        return $this->belongsTo(Noticia::class);
    }
}
