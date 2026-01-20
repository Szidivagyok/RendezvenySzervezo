<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
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
            'cityName' => 'required|string|max:255',
            'zipCode' => 'required|string|max:10',
            'street' => 'required|string|max:255',
            'houseNumber' => 'required|string|max:10',
            'locationName' => 'required|string|max:255',
            'maxCapacity' => 'required|integer|min:1',
            'minCapacity' => 'required|integer|min:1',
            'priceSlashPerson' => 'required|decimal|min:0',
            'roomPriceSlashDay' => 'required|decimal|min:0',
            //
        ];
    }
}
