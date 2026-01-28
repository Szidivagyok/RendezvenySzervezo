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
        return false;
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
                'required',
                'integer',
                Rule::unique('services')->where(
                    fn($q)=> $q->where('service', request('service'))
                  
                ),
            ],
              'price' => ['required', 'integer', 'min:6000'],
        ];
    }
}
