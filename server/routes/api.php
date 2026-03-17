<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocationsPictureController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderServiceController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\UserController;
use App\Models\LocationsPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//endpoint
Route::get('/x', function () {
    return 'API';
});


//region users
//User kezelés, login, logout
//Mindenki
Route::post('users/login', [UserController::class, 'login']);
Route::post('users/logout', [UserController::class, 'logout']);
Route::post('users', [UserController::class, 'store']);

//Admin: 
//minden user lekérdezése
Route::get('users', [UserController::class, 'index'])
    ->middleware('auth:sanctum', 'ability:admin');
//Egy user lekérése    
Route::get('users/{id}', [UserController::class, 'show'])
    ->middleware('auth:sanctum', 'ability:admin');
//User adatok módosítása      
Route::patch('users/{id}', [UserController::class, 'update'])
    ->middleware('auth:sanctum', 'ability:admin');
//User törlés
Route::delete('users/{id}', [UserController::class, 'destroy'])
    ->middleware('auth:sanctum', 'ability:admin');

//User self (Amit a user önmagával csinálhat) parancsok
Route::delete('usersme', [UserController::class, 'destroySelf'])
    ->middleware('auth:sanctum', 'ability:usersme:delete');

Route::patch('usersme', [UserController::class, 'updateSelf'])
    ->middleware('auth:sanctum', 'ability:usersme:patch');

Route::patch('usersmeupdatepassword', [UserController::class, 'updatePassword'])
    ->middleware('auth:sanctum', 'ability:usersme:updatePassword');

Route::get('usersme', [UserController::class, 'indexSelf'])
    ->middleware('auth:sanctum', 'ability:usersme:get');



Route::get('locations', [LocationController::class, 'index']);
Route::get('locations/{id}', [LocationController::class, 'show']);
Route::post('locations', [LocationController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:locations:post']);
Route::patch('locations/{id}', [LocationController::class, 'update'])
    ->middleware(['auth:sanctum', 'ability:locations:patch']);
Route::delete('locations/{id}', [LocationController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:locations:delete']);

Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::post('orders', [OrderController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:orders:post']);
Route::patch('orders/{id}', [OrderController::class, 'update'])
    ->middleware(['auth:sanctum','ability:orders:patch']);
Route::delete('orders/{id}', [OrderController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:orders:delete']);

Route::get('orderServices', [OrderServiceController::class, 'index']);
Route::get('orderServices/{id}', [OrderServiceController::class, 'show']);
Route::post('orderServices', [OrderServiceController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:orderServices:post']);
Route::patch('orderServices/{id}', [OrderServiceController::class, 'update'])
    ->middleware(['auth:sanctum','ability:orderServices:patch']);
Route::delete('orderServices/{id}', [OrderServiceController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:orderServices:delete']);

Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{id}', [ServiceController::class, 'show']);
Route::post('services', [ServiceController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:services:post']);
Route::patch('services/{id}', [ServiceController::class, 'update'])
    ->middleware(['auth:sanctum','ability:services:patch']);
Route::delete('services/{id}', [ServiceController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:services:delete']);

Route::get('locations_pictures', [LocationsPictureController::class, 'index']);
Route::get('locations_pictures/{id}', [LocationsPictureController::class, 'show']);
Route::post('locations_pictures', [LocationsPictureController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:locations_pictures:post']);
Route::patch('locations_pictures/{id}', [LocationsPictureController::class, 'update'])
    ->middleware(['auth:sanctum','ability:locations_pictures:patch']);
Route::delete('locations_pictures/{id}', [LocationsPictureController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:locations_pictures:delete']);

Route::get('pictures', [PictureController::class, 'index']);
Route::get('pictures/{id}', [PictureController::class, 'show']);
Route::get('locationpictures/{id}', [PictureController::class, 'locationpictures']);
Route::get('servicepictures/{id}', [PictureController::class, 'servicepictures']);
Route::post('pictures', [PictureController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:pictures:post']);
Route::patch('pictures/{id}', [PictureController::class, 'update'])
    ->middleware(['auth:sanctum','ability:ictures:patch']);
Route::delete('pictures/{id}', [PictureController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:pictures:delete']);

Route::get('service_types', [ServiceTypeController::class, 'index']);
Route::get('service_types/{id}', [ServiceTypeController::class, 'show']);
Route::post('service_types', [ServiceTypeController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:service_types:post']);
Route::patch('service_types/{id}', [ServiceTypeController::class, 'update'])
    ->middleware(['auth:sanctum','ability:ictures:patch']);
Route::delete('service_types/{id}', [ServiceTypeController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'ability:service_types:delete']);
//endregion
