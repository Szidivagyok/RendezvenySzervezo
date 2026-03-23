<?php

namespace App\Http\Controllers;

use App\Models\Location as CurrentModel;
use App\Http\Requests\StoreLocationRequest as StoreCurrentModelRequest;
use App\Http\Requests\UpdateLocationRequest as UpdateCurrentModelRequest;
use App\Models\Location;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->apiResponse(
            function () {
                return CurrentModel::all();
            }
        );
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    // Minden mezőt fel kell sorolni, amit el akarsz menteni!
    $validated = $request->validate([
        'cityName' => 'required|string',
        'zipCode' => 'required',
        'street' => 'required',
        'locationName' => 'required',
        'houseNumber' => 'required', // Fontos!
        'maxCapacity' => 'nullable|numeric',
        'minCapacity' => 'nullable|numeric',
        'priceSlashPerson' => 'nullable|numeric',
        'roomPriceSlashDay' => 'nullable|numeric',
    ]);

    // Most már a $validated-ben benne lesz a houseNumber is
    $location = Location::create($validated);
    
    return response()->json($location, 201);
}

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return $this->apiResponse(function () use ($id) {
            return CurrentModel::findOrFail($id);
        });
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, int $id) // Módosítva Request-re
{
    return $this->apiResponse(function () use ($request, $id) {
        $row = CurrentModel::findOrFail($id);
        
        // Itt is fontos, hogy ne csak a validáltat, hanem mindent frissítsünk, 
        // vagy adjuk hozzá a houseNumber-t a validációhoz!
        $row->update($request->all()); 
        
        return $row;
    });
}

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(int $id)
    {
        return $this->apiResponse(function () use ($id) {
            CurrentModel::findOrFail($id)->delete();
            return ['id' => $id];
        });
    }
}