<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PublishToBotsRequest extends FormRequest
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

        return [
            'titulo' => ['required', 'string', 'max:80'],
            'contenido' => ['required', 'string'],
            'image' => ['required', 'image'],
            'bots' => ['required', 'array'],
            'bots.*' => [
                'required',
                'uuid',
                Rule::exists('bots', 'id')->where('user_id', $userId)
            ]
        ];
    }

    public function messages()
    {
        return [
            'bots.*.exists' => 'Este Bot no le pertenece'
        ];
    }
}
