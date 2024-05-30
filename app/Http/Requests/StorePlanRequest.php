<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required'],
            'short_name' => ['required', Rule::unique('plans')],
            'price' => ['required'],
            'term_number' => ['required'],
            'term_type_time' => ['required'],
        ];
    }

    public function columns(): array
    {
        return ['short_name' => 'Nombre corto'];
    }
}
