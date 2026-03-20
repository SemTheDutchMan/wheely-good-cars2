<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
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
            'price' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'sold' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'De prijs is verplicht.',
            'sold.required' => 'De status is verplicht.',
        ];
    }
}
