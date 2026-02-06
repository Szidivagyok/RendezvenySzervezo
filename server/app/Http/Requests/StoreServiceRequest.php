<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'service' => 'required|string|unique:services,service',
            'price' => 'required|numeric|min:6000',
        ];

    }

    public function messages(): array
{
    return [
        'service.required' => 'A szolgáltatás megadása kötelező.',
        'service.string'   => 'A szolgáltatás neve csak szöveg lehet.',
        'service.unique'   => 'Ez a szolgáltatás már létezik.',

        'price.required' => 'Az ár megadása kötelező.',
        'price.numeric'  => 'Az ár csak szám lehet.',
        'price.min'      => 'Az ár minimum 6000 Ft lehet.',
    ];
}
}
