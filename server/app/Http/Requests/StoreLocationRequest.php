<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreLocationRequest extends FormRequest
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
            'zipCode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('locations')
                    ->where(
                        fn($q) => $q
                            ->where('street', request('street'))
                            ->where('houseNumber', request('houseNumber'))
                            ->where('locationName', request('locationName'))
                    ),
            ],

            'cityName.required' => 'A város megadása kötelező.',
            'cityName.string' => 'A város neve csak szöveg lehet.',
            'cityName.max' => 'A város neve legfeljebb 255 karakter lehet.',

            'street.required' => 'Az utca megadása kötelező.',
            'street.string' => 'Az utca neve csak szöveg lehet.',
            'street.max' => 'Az utca neve legfeljebb 255 karakter lehet.',
            'street.unique' => 'Ezen a címen már létezik helyszín.',

            'houseNumber.required' => 'A házszám megadása kötelező.',
            'houseNumber.string' => 'A házszám csak szöveg lehet.',
            'houseNumber.max' => 'A házszám legfeljebb 50 karakter lehet.',
            'houseNumber.unique' => 'Ezen a címen már létezik helyszín.',

            'locationName.required' => 'A helyszín neve kötelező.',
            'locationName.string' => 'A helyszín neve csak szöveg lehet.',
            'locationName.max' => 'A helyszín neve legfeljebb 255 karakter lehet.',
            'locationName.unique' => 'Ez a helyszín már létezik ezen a címen.',

            'maxCapacity.required' => 'A maximális kapacitás megadása kötelező.',
            'maxCapacity.integer' => 'A maximális kapacitás csak egész szám lehet.',
            'maxCapacity.min' => 'A maximális kapacitás minimum 1 lehet.',

            'minCapacity.required' => 'A minimális kapacitás megadása kötelező.',
            'minCapacity.integer' => 'A minimális kapacitás csak egész szám lehet.',
            'minCapacity.min' => 'A minimális kapacitás minimum 1 lehet.',

            'priceSlashPerson.required' => 'Az egy főre jutó ár megadása kötelező.',
            'priceSlashPerson.decimal' => 'Az egy főre jutó ár csak egész szám lehet.',
            'priceSlashPerson.min' => 'Az egy főre jutó ár nem lehet negatív.',

            'roomPriceSlashDay.required' => 'A napi teremár megadása kötelező.',
            'roomPriceSlashDay.decimal' => 'A napi teremár csak egész szám lehet.',
            'roomPriceSlashDay.min' => 'A napi teremár nem lehet negatív.',
        ];
    }
}
