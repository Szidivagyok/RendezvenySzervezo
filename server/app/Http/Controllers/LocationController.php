<?php
 
namespace App\Http\Controllers;
 
use App\Models\Location;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use Illuminate\Database\QueryException;
 
class LocationController extends Controller
{
    public function index()
    {
        try {
            $rows = Location::all();
 
            return response()->json([
                'message' => 'OK',
                'data' => $rows
            ], 200, options: JSON_UNESCAPED_UNICODE);
 
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Server error: {$e->getCode()}",
                'data' => []
            ], 500, options: JSON_UNESCAPED_UNICODE);
        }
    }
 
    public function store(StoreLocationRequest $request)
    {
        try {
            $row = Location::create($request->all());
 
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
 
    public function show(int $id)
    {
        $row = Location::find($id);
 
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
 
    public function update(UpdateLocationRequest $request, int $id)
    {
        $row = Location::find($id);
 
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
 
    public function destroy(int $id)
    {
        $row = Location::find($id);
 
        if ($row) {
            $row->delete();
 
            return response()->json([
                'message' => 'OK',
                'data' => ['id' => $id]
            ], 200, options: JSON_UNESCAPED_UNICODE);
        }
 
        return response()->json([
            'message' => "Delete error. Not found id: $id",
            'data' => null
        ], 404, options: JSON_UNESCAPED_UNICODE);
    }
}