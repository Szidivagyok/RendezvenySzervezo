<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'zipCode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('locations')->where(
                    fn($q) =>
                    $q->where('zipCode', request('zipCode'))
                        ->where('street', request('street'))
                        ->where('houseNumber', request('houseNumber'))
                        ->where('locationName', request('locationName'))
                ),
            ],

            'cityName' => ['required', 'string', 'max:255'],
            'maxCapacity' => ['required', 'integer', 'min:1'],
            'minCapacity' => ['required', 'integer', 'min:1'],
            'priceSlashPerson' => ['required', 'decimal:0', 'min:0'],
            'roomPriceSlashDay' => ['required', 'decimal:0', 'min:0'],
        ];
    }
}
