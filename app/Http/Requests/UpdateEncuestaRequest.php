<?php

namespace App\Http\Requests;

use App\Models\BotDestination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateEncuestaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = Auth::id();
        $userDestinations = [];
        if ($userId) {
            $userDestinations = BotDestination::query()->whereHas('bot', function (Builder $query) use ($userId) {
                $query->whereHas('user', function (Builder $query) use ($userId) {
                    $query->where('id', $userId);
                });
            })->pluck('bot_destinations.id',)->all();
        }

        return [
            'titulo' => ['required', 'string', 'max:80'],
            'opciones' => ['required', 'string'],
            'botDestinations' => ['sometimes', 'array'],
            'botDestinations.*' => [
                'sometimes',
                'uuid',
                Rule::in($userDestinations)
            ]
        ];
    }

    public function messages()
    {
        return [
            'botDestinations.*.in' => 'Este destino no se encuentra dentro sus Bots'
        ];
    }
}
