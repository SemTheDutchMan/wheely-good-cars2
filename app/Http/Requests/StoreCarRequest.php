<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'license_plate' => ['required', 'string', 'max:16'],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'mileage' => ['required', 'integer', 'min:0', 'max:1000000'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:12'],
            'doors' => ['nullable', 'integer', 'min:1', 'max:8'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'weight' => ['nullable', 'integer', 'min:100', 'max:10000'],
            'color' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'license_plate.required' => 'Het kenteken is verplicht.',
            'make.required' => 'Het merk is verplicht.',
            'model.required' => 'Het model is verplicht.',
            'price.required' => 'De prijs is verplicht.',
            'mileage.required' => 'De kilometerstand is verplicht.',
        ];
    }
}
