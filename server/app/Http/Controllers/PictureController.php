<?php

namespace App\Http\Controllers;

use App\Models\Picture as CurrentModel;
use App\Http\Requests\StorePictureRequest as StoreCurrentModelRequest;
use App\Http\Requests\UpdatePictureRequest as UpdateCurrentModelRequest;
use App\Models\Picture;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PictureController extends Controller
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
    public function store(StoreCurrentModelRequest $request)
    {
        return $this->apiResponse(
            function () use ($request) {
                return CurrentModel::create($request->validated());
            }
        );
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

   public function locationpictures(int $id)
{
    return $this->apiResponse(function () use ($id) {
        return Picture::whereHas('locations', function ($query) use ($id) {
            $query->where('locations.id', $id);
        })
        // Itt hozzáadtuk az 'id'-t a select-hez
        ->select('id', 'pictureName') 
        ->get();
    });
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrentModelRequest $request, int $id)
    {
        return $this->apiResponse(function () use ($request, $id) {
            $row = CurrentModel::findOrFail($id);
            $row->update($request->validated());
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
