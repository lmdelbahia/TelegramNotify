<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoticiaImagenRequest extends FormRequest
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
        return [
            'noticia_id' => ['required', 'integer', 'exists:noticias,id'],
            'imagen' => ['required', 'image'],
            'descripcion' => ['nullable', 'string', 'max:150']
        ];
    }
}
