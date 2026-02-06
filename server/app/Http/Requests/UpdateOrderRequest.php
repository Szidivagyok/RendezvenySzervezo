<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateOrderRequest extends FormRequest
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
            'userId' => ['sometimes', 'required', 'integer'],
            'locationId' => ['sometimes', 'required', 'integer', 'exists:locations,id'],
            'orderTime' => ['sometimes', 'required', 'date'],

            'howManyPeople' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'howManyDays' => ['sometimes', 'required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            // PATCH miatt: ha nincs minden adat elküldve,
            // a meglévő rekordból vesszük
            $orderId = $this->route('id');

            $current = DB::table('orders')->where('id', $orderId)->first();
            if (!$current) {
                return;
            }

            // 🔑 1️⃣ ELŐBB: effektív értékek (PATCH miatt)
            $userId = $this->userId ?? $current->userId;
            $locationId = $this->locationId ?? $current->locationId;
            $orderTime = $this->orderTime ?? $current->orderTime;
            $howManyDays = $this->howManyDays ?? $current->howManyDays;

            // 🔑 2️⃣ ha idő szempontból SEMMI nem változott → ENGEDJÜK
            if (
                $userId == $current->userId &&
                $locationId == $current->locationId &&
                Carbon::parse($orderTime)->equalTo(Carbon::parse($current->orderTime)) &&
                $howManyDays == $current->howManyDays
            ) {
                return;
            }
            $userId = $this->userId ?? $current->userId;
            $locationId = $this->locationId ?? $current->locationId;
            $orderTime = $this->orderTime ?? $current->orderTime;
            $howManyDays = $this->howManyDays ?? $current->howManyDays;

            // dátum-alapú logika
            $start = Carbon::parse($orderTime)->startOfDay();
            $end = $start->copy()->addDays($howManyDays);

            $conflict = DB::table('orders')
                ->where('id', '!=', $orderId) // 🔑 ÖNMAGÁT KIZÁRJUK
                ->where('userId', $userId)
                ->where('locationId', $locationId)
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
                    'Ez a módosítás időben ütközik egy másik meglévő rendeléseddel ezen a helyszínen.'
                );
            }
        });
    }

    public function messages(): array
    {

        return [
            'userId.required' => 'A felhasználó azonosítója kötelező.',
            'userId.integer' => 'A felhasználó azonosítója csak szám lehet.',
            'userId.unique' => 'Erre az időpontra már létezik foglalás ezen a helyszínen.',

            'locationId.required' => 'A helyszín azonosítója kötelező.',
            'locationId.integer' => 'A helyszín azonosítója csak szám lehet.',
            'locationId.exists' => 'A megadott helyszín nem létezik.',

            'orderTime.required' => 'A foglalás időpontja kötelező.',
            'orderTime.date' => 'A foglalás időpontja nem megfelelő dátum formátum.',

            'howManyPeople.integer' => 'A résztvevők száma csak egész szám lehet.',
            'howManyPeople.min' => 'A résztvevők száma minimum 1 lehet.',

            'howManyDays.required' => 'A napok száma kötelező.',
            'howManyDays.integer' => 'A napok száma csak egész szám lehet.',
            'howManyDays.min' => 'A napok száma minimum 1 lehet.',
        ];
    }
}
