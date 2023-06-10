<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bot extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'token'
    ];

    //Scopes

    //Helpers

    //Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function botDestinations(): HasMany
    {
        return $this->hasMany(BotDestination::class);
    }
}
