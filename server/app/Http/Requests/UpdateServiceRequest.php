<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
        'service' => [
            'sometimes',
            'string',
            'max:255',
            Rule::unique('services', 'service')->ignore($this->route('id')),
        ],
        'price' => 'sometimes|numeric|min:1',
    ];
    }

    public function messages(): array
    {
        return [
            'service.string' => 'A szolgáltatás neve csak szöveg lehet.',
            'service.max'    => 'A szolgáltatás neve maximum 255 karakter lehet.',
            'service.unique' => 'Ez a szolgáltatás már létezik.',

            'price.numeric' => 'Az ár csak szám lehet.',
            'price.min'     => 'Az ár minimum 1 Ft lehet.',
        ];
    }

}
