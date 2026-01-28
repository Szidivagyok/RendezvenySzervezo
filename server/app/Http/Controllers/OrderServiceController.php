<?php

namespace App\Http\Controllers;

use App\Models\OrderService as CurrentModel;
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
                return CurrentModel::all();
                // $sql = "SELECT * FROM sports";
                // $rows = DB::select($sql);
                // return $rows;
            }
        );
    }

    public function show(int $id)
    {
        return $this->apiResponse(function () use ($id) {
            return CurrentModel::findOrFail($id);
        });
    }

    public function store(StoreCurrentModelRequest $request)
    {
        return $this->apiResponse(
            function () use ($request) {
                return CurrentModel::create($request->validated());
            }
        );
    }

    public function update(UpdateCurrentModelRequest $request, int $id)
    {
        return $this->apiResponse(function () use ($request, $id) {
            $row = CurrentModel::findOrFail($id);
            $row->update($request->validated());
            return $row;
        });
    }

    public function destroy($id)
    {
        return $this->apiResponse(function () use ($id) {
            CurrentModel::findOrFail($id)->delete();
            return ['id' => $id];
        });
    }

}
