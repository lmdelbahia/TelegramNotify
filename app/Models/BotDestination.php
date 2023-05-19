<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'name',
        'identifier'
    ];

    //Scopes

    //Helpers

    //Relaciones
    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }
}
