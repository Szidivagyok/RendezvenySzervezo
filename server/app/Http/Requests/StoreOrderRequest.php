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
            'userId' => ['required', 'integer'],
            'locationId' => ['required', 'integer', 'exists:locations,id'],
            'orderTime' => ['required', 'date'],

            'howManyPeople' => ['nullable', 'integer', 'min:1'],
            'howManyDays' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            $start = Carbon::parse($this->orderTime);
            $end   = $start->copy()->addDays($this->howManyDays);

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
