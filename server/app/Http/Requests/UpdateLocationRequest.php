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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = $this->route('id'); // vagy 'location', attól függ mi a route paraméter neve

        return [
            'zipCode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('locations')
                    ->ignore($id, 'id')
                    ->where(
                        fn($q) => $q
                            ->where('zipCode', request('zipCode'))
                            ->where('street', request('street'))
                            ->where('houseNumber', request('houseNumber'))
                            ->where('locationName', request('locationName'))
                    ),
            ],
        ];


    }


    public function messages(): array
    {
        return [

            'cityName.required' => 'A város megadása kötelező.',
            'cityName.string' => 'A város neve csak szöveg lehet.',
            'cityName.max' => 'A város neve legfeljebb 255 karakter lehet.',

            'zipCode.required' => 'Az irányítószám megadása kötelező.',
            'zipCode.string' => 'Az irányítószám csak szöveg lehet.',
            'zipCode.max' => 'Az irányítószám legfeljebb 10 karakter lehet.',

            'street.required' => 'Az utca megadása kötelező.',
            'street.string' => 'Az utca neve csak szöveg lehet.',
            'street.max' => 'Az utca neve legfeljebb 255 karakter lehet.',

            'houseNumber.required' => 'A házszám megadása kötelező.',
            'houseNumber.string' => 'A házszám csak szöveg lehet.',
            'houseNumber.max' => 'A házszám legfeljebb 50 karakter lehet.',

            'locationName.required' => 'A helyszín neve kötelező.',
            'locationName.string' => 'A helyszín neve csak szöveg lehet.',
            'locationName.max' => 'A helyszín neve legfeljebb 255 karakter lehet.',

            'maxCapacity.required' => 'A maximális kapacitás megadása kötelező.',
            'maxCapacity.integer' => 'A maximális kapacitás csak egész szám lehet.',
            'maxCapacity.min' => 'A maximális kapacitás minimum 1 lehet.',

            'minCapacity.required' => 'A minimális kapacitás megadása kötelező.',
            'minCapacity.integer' => 'A minimális kapacitás csak egész szám lehet.',
            'minCapacity.min' => 'A minimális kapacitás minimum 1 lehet.',

            'priceSlashPerson.required' => 'Az egy főre jutó ár megadása kötelező.',
            'priceSlashPerson.decimal' => 'Az egy főre jutó ár csak szám lehet.',
            'priceSlashPerson.min' => 'Az egy főre jutó ár nem lehet negatív.',

            'roomPriceSlashDay.required' => 'A napi teremár megadása kötelező.',
            'roomPriceSlashDay.decimal' => 'A napi teremár csak szám lehet.',
            'roomPriceSlashDay.min' => 'A napi teremár nem lehet negatív.',

            'zipCode.unique' => 'Ezen a címen már létezik helyszín.', // összetett unique üzenet
        ];
    }
}
