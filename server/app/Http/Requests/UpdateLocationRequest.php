<?php

namespace App\Http\Requests;

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
            //
            'cityName' => 'sometimes|string|max:255',
            'zipCode' => 'sometimes|string|max:10',
            'street' => 'sometimes|string|max:255',
            'houseNumber' => 'sometimes|string|max:10',
            'locationName' => 'sometimes|string|max:255',
            'maxCapacity' => 'sometimes|integer|min:1',
            'minCapacity' => 'sometimes|integer|min:1',
            'priceSlashPerson' => 'sometimes|decimal|min:0',
            'roomPriceSlashDay' => 'sometimes|decimal|min:0',
        ];
    }
}
