<?php

namespace App\Http\Controllers;

use App\Models\Order as CurrentModel;
use App\Http\Requests\StoreOrderRequest as StoreCurrentModelRequest;
use App\Http\Requests\UpdateOrderRequest as UpdateCurrentModelRequest;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        return $this->apiResponse(
            function () {
                $user = Auth::user();

                // Ha admin, akkor látja az összes foglalást (a seeder-eset is)
                if ($user && $user->isAdmin()) {
                    return CurrentModel::all();
                }

                // Ha megrendelő, csak a sajátját látja (userId alapján) 
                // ÉS csak azt, ami NEM rendszer-adat (is_system = false)
                return CurrentModel::where('userId', $user->id)
                                   ->where('is_system', false)
                                   ->get();
            }
        );
    }

    public function show(int $id)
    {
        return $this->apiResponse(function () use ($id) {
            $user = Auth::user();
            $row = CurrentModel::findOrFail($id);

            // Védelem: Ha nem admin, ne tudjon másét vagy rendszer-adatot megnézni ID alapján
            if (!$user->isAdmin() && ($row->userId !== $user->id || $row->is_system)) {
                abort(403, 'Nincs jogosultsága a megtekintéshez.');
            }

            return $row;
        });
    }

    public function store(StoreCurrentModelRequest $request)
    {
        return $this->apiResponse(
            function () use ($request) {
                $data = $request->validated();
                
                // Automatikusan hozzáadjuk a bejelentkezett user ID-ját
                $data['userId'] = Auth::id();
                // Biztosítjuk, hogy amit a user hoz létre, az NEM rendszer-adat
                $data['is_system'] = false;

                return CurrentModel::create($data);
            }
        );
    }

    public function update(UpdateCurrentModelRequest $request, int $id)
    {
        return $this->apiResponse(function () use ($request, $id) {
            $user = Auth::user();
            $row = CurrentModel::findOrFail($id);

            // Védelem: Csak a sajátját módosíthatja (ha nem admin)
            if (!$user->isAdmin() && ($row->userId !== $user->id || $row->is_system)) {
                abort(403, 'Nincs jogosultsága a módosításhoz.');
            }

            $row->update($request->validated());
            return $row;
        });
    }

    public function destroy($id)
    {
        return $this->apiResponse(function () use ($id) {
            $user = Auth::user();
            $row = CurrentModel::findOrFail($id);

            // Védelem: Csak az admin törölhet, vagy a user a sajátját (ha megengeded)
            // Itt most csak az admint engedjük törölni, ahogy kérted:
            if (!$user->isAdmin()) {
                abort(403, 'Csak adminisztrátor törölhet foglalást.');
            }

            $row->delete();
            return ['id' => $id];
        });
    }
}