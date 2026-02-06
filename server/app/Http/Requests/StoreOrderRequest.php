<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreOrderRequest extends FormRequest
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
            'userId.required' => 'A felhasználó azonosítója kötelező.',
            'userId.integer' => 'A felhasználó azonosítója csak szám lehet.',

            'locationId.required' => 'A helyszín azonosítója kötelező.',
            'locationId.integer' => 'A helyszín azonosítója csak szám lehet.',
            'locationId.exists' => 'A megadott helyszín nem létezik.',

            'orderTime.required' => 'A foglalás időpontja kötelező.',
            'orderTime.date' => 'A foglalás időpontja nem megfelelő dátum formátum.',

            'userId.unique' => 'Erre az időpontra már létezik foglalás ezen a helyszínen.',

            'howManyPeople.integer' => 'A résztvevők száma csak egész szám lehet.',
            'howManyPeople.min' => 'A résztvevők száma minimum 1 lehet.',

            'howManyDays.required' => 'A napok száma kötelező.',
            'howManyDays.integer' => 'A napok száma csak egész szám lehet.',
            'howManyDays.min' => 'A napok száma minimum 1 lehet.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            $start = Carbon::parse($this->orderTime);
            $end = $start->copy()->addDays($this->howManyDays);

            $conflict = DB::table('orders')
                ->where('userId', $this->userId)
                ->where('locationId', $this->locationId)
                ->where(function ($q) use ($start, $end) {
                    $q->where('orderTime', '<', $end)
                        ->whereRaw(
                            'DATE_ADD(orderTime, INTERVAL howManyDays DAY) > ?',
                            [$start]
                        );
                })
                ->exists();

            if ($conflict) {
                $validator->errors()->add(
                    'order',
                    'Ez a foglalás időben ütközik egy meglévő rendeléseddel ezen a helyszínen.'
                );
            }
        });
    }
}
