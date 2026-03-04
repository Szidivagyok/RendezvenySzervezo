<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationsPictureRequest extends FormRequest
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
        $id = $this->route('id'); // route paraméter neve

        return [

            'locationId' => [
                'required',
                'integer',
            ],

            'pictureId' => [
                'required',
                'integer',
                Rule::unique('locations_pictures')
                    ->ignore($id, 'id')
                    ->where(
                        fn($q) => $q
                            ->where('locationId', request('locationId'))
                    ),
            ],
        ];
    }
}
