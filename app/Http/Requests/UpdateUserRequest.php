<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'role' => [Rule::enum(UserRoleEnum::class)],
            'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'password' => ['confirmed', Rules\Password::defaults()],
        ];
    }
}
