<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingSelectionController extends Controller
{
    public function getAll()
    {
        // Az összes rendelés lekérése
        $orders = Order::all();

        // Minden rendeléshez hozzácsatoljuk a szolgáltatásait
        foreach ($orders as $order) {
            $order->services = DB::table('orderServices')
                ->join('services', 'orderServices.serviceId', '=', 'services.id')
                ->where('orderServices.orderId', $order->id)
                ->select('services.service', 'services.serviceTypeId')
                ->get();
        }

        return response()->json($orders);
    }
}