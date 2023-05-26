<?php

namespace App\Http\Resources;

use App\Helpers\FieldsOptions\RoleFieldOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => optional(RoleFieldOptions::tryFrom($this->role))->label(),
            'created_at' => Carbon::parse($this->created_at)->locale(config('app.locale'))->isoFormat('DD MMMM Y'),
            'updated_at' => Carbon::parse($this->updated_at)->locale(config('app.locale'))->isoFormat('DD MMMM Y'),
            //Complementarios
            'bots_count' => $this->whenNotNull($this->bots_count),
            'noticias_count' => $this->whenNotNull($this->noticias_count)
        ];
    }
}
