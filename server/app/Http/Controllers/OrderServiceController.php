<?php

namespace App\Http\Controllers;

use App\Models\OrderService;
use App\Http\Requests\StoreOrderServiceRequest;
use App\Http\Requests\UpdateOrderServiceRequest;
use Illuminate\Database\QueryException;

class OrderServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
          try {
            $rows = OrderService::all();

            $status = 200;
            $data = [
                'message' => 'OK',
                'data' => $rows
            ];
        } catch (\Exception $e) {
            $status = 500;
            $data = [
                'message' => "Server error: {$e->getCode()}",
                'data' => $rows
            ];
        }

        return response()->json($data, $status, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderServiceRequest $request)
    {
        //
        try {
            $row = OrderService::create($request->all());
 
            return response()->json([
                'message' => 'OK',
                'data' => $row
            ], 201, options: JSON_UNESCAPED_UNICODE);
 
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Insert error',
                'data' => null
            ], 400, options: JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
        $row = OrderService::find($id);
 
        if ($row) {
            return response()->json([
                'message' => 'OK',
                'data' => $row
            ], 200, options: JSON_UNESCAPED_UNICODE);
        }
 
        return response()->json([
            'message' => "Not found id: $id",
            'data' => null
        ], 404, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderServiceRequest $request, int $id)
    {
        //
          $row = OrderService::find($id);
 
        if ($row) {
            $row->update($request->all());
 
            return response()->json([
                'message' => 'OK',
                'data' => $row
            ], 200, options: JSON_UNESCAPED_UNICODE);
        }
 
        return response()->json([
            'message' => "Patch error. Not found id: $id",
            'data' => null
        ], 404, options: JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
         $row = OrderService::find( $id);
 
        if ($row) {
            $row->delete();
 
            return response()->json([
                'message' => 'OK',
                'data' => [
                    'id' => $id
                ]
            ], 200, options: JSON_UNESCAPED_UNICODE);
        }
 
        return response()->json([
            'message' => "Delete error. Not found id: $id",
            'data' => null
        ], 404, options: JSON_UNESCAPED_UNICODE);
    }
}
