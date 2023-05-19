<?php

namespace App\Http\Requests;

use App\Helpers\FieldsOptions\RoleFieldOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user');

        $ere = $this;

        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', "unique:users,email,{$user->id},id"],
            'password' => [Rule::excludeIf(!$this->cbxChangePassword), 'required', 'string', 'confirmed', 'min:8', 'max:250'],
            'role' => ['required', new Enum(RoleFieldOptions::class)]
        ];
    }
}
