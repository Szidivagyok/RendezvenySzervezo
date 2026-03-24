<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Itt kell megadni, hogy MELYIK mezőre MILYEN szabály vonatkozik.
     */
    public function rules(): array
    {
        return [
            // Marad a userId (Nagy I), ahogy kérted
            'userId'        => 'required|integer|exists:users,id',
            'locationId'    => 'required|integer|exists:locations,id',
            'orderTime'     => 'required|date',
            'howManyPeople' => 'required|integer|min:1',
            'howManyDays'   => 'required|integer|min:1',
        ];
    }

    /**
     * Ide kerülnek a saját hibaüzenetek (opcionális).
     */
    public function messages(): array
    {
        return [
            'userId.required' => 'A felhasználó azonosítója kötelező.',
            'userId.exists'   => 'A megadott felhasználó nem létezik.',
            'locationId.required' => 'A helyszín azonosítója kötelező.',
            'locationId.exists'   => 'A megadott helyszín nem létezik.',
            'orderTime.required'  => 'A foglalás időpontja kötelező.',
            'orderTime.date'      => 'A foglalás időpontja nem megfelelő dátum formátum.',
            'howManyPeople.min'   => 'A résztvevők száma minimum 1 lehet.',
            'howManyDays.required' => 'A napok száma kötelező.',
        ];
    }

    // public function withValidator($validator): void
    // {
    //     $validator->after(function ($validator) {
    //         // Csak akkor fusson az ütközésvizsgálat, ha az alapadatok érvényesek
    //         if ($validator->errors()->any()) return;

    //         // A biztonság kedvéért pontosítjuk a változókat a kérésből
    //         $orderTime = $this->input('orderTime');
    //         $howManyDays = $this->input('howManyDays');
    //         $uId = $this->input('userId');
    //         $lId = $this->input('locationId');

    //         $start = Carbon::parse($orderTime);
    //         $end = $start->copy()->addDays($howManyDays);

    //         $conflict = DB::table('orders')
    //             ->where('userId', $uId) // Itt biztosan a táblázatbeli userId-t nézzük
    //             ->where('locationId', $lId)
    //             ->where(function ($q) use ($start, $end) {
    //                 $q->where('orderTime', '<', $end)
    //                     ->whereRaw(
    //                         'DATE_ADD(orderTime, INTERVAL howManyDays DAY) > ?',
    //                         [$start]
    //                     );
    //             })
    //             ->exists();

    //         if ($conflict) {
    //             $validator->errors()->add(
    //                 'orderTime',
    //                 'Ez a foglalás időben ütközik egy meglévő rendeléseddel ezen a helyszínen.'
    //             );
    //         }
    //     });
    // }
}