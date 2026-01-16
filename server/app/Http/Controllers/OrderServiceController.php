<?php

namespace App\Http\Controllers;

use App\Models\OrderService;
use App\Http\Requests\StoreOrderServiceRequest;
use App\Http\Requests\UpdateOrderServiceRequest;

class OrderServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderServiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderService $orderService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderServiceRequest $request, OrderService $orderService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderService $orderService)
    {
        //
    }
}
