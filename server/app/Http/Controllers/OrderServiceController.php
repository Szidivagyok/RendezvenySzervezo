<?php

namespace App\Http\Controllers;

use App\Models\OrderService;
use App\Http\Requests\StoreOrderServiceRequest as StoreCurrentModelRequest;
use App\Http\Requests\UpdateOrderServiceRequest as UpdateCurrentModelRequest;
use Illuminate\Database\QueryException;

class OrderServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        return $this->apiResponse(
            function () {
                return OrderService::all();
                // $sql = "SELECT * FROM sports";
                // $rows = DB::select($sql);
                // return $rows;
            }
        );
    }

    public function show(int $id)
    {
        return $this->apiResponse(function () use ($id) {
            return OrderService::findOrFail($id);
        });
    }

    public function store(StoreCurrentModelRequest $request)
    {
        return $this->apiResponse(
            function () use ($request) {
                return OrderService::create($request->validated());
            }
        );
    }

    public function update(UpdateCurrentModelRequest $request, int $id)
    {
        return $this->apiResponse(function () use ($request, $id) {
            $row = OrderService::findOrFail($id);
            $row->update($request->validated());
            return $row;
        });
    }

    public function destroy($id)
    {
        return $this->apiResponse(function () use ($id) {
            OrderService::findOrFail($id)->delete();
            return ['id' => $id];
        });
    }

}
