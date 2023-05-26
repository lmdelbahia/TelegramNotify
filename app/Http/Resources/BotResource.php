<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            //'user_id' => $this->user_id,
            'name' => $this->name,
            //'token' => $this->token,
            //Complementarios
            //Relaciones
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
