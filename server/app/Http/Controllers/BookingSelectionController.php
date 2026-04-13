<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingSelectionController extends Controller
{
  public function getBookingDetails(int $id)
{
    return $this->apiResponse(
        function () use ($id){
            $order = Order::findOrFail($id);

            // Helyszín lekérése
            $order->locationName = DB::table('locations')
                ->where('id', $order->locationId)
                ->value('locationName'); 

            // Szolgáltatások lekérése
            $services = DB::table('order_services')
                ->join('services', 'order_services.serviceId', '=', 'services.id')
                ->where('order_services.orderId', $order->id)
                ->select('services.service', 'services.serviceTypeId')
                ->get();

            // Ha van kiválasztott szolgáltatás, hozzáadjuk, ha nincs, nem fog szerepelni a JSON-ben
            if ($services->isNotEmpty()) {
                $order->services = $services;
            }

            return $order;
        }
    );
}
}