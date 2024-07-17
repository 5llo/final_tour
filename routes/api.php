<?php

use App\Http\Controllers\AllToursController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthCustomerController;
use App\Http\Controllers\Api\AuthDesignerController;
use App\Http\Controllers\Api\AuthOfferController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TourController;
use App\Models\Customer;
use App\Models\Designer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Route::middleware('auth:sanctum')->post('/customer/logout', [AuthCustomerController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  //  return $request->user();
});

Route::post('/customer/login',[AuthCustomerController::class,'login']);
Route::post('/customer/register',[AuthCustomerController::class,'register']);

Route::post('/designer/login',[AuthDesignerController::class,'login']);
Route::post('/offer/login',[AuthOfferController::class,'login']);



Route::middleware(['auth:sanctum','role:'.Designer::class])->group(function () {

     Route::resource('tour', TourController::class);
     Route::post('tour/show', [TourController::class,'show']);
     Route::post('tour/index', [TourController::class,'index']);
     Route::post('tour/delete', [TourController::class,'destroy']);
     Route::post('tour/updat', [TourController::class,'update']);
    Route::post('/customer/logout', [AuthCustomerController::class, 'logout']);
    Route::post('/designer/logout', [AuthDesignerController::class, 'logout']);
    Route::post('/offer/logout', [AuthOfferController::class, 'logout']);


});

Route::middleware(['auth:sanctum','role:'.Customer::class])->group(function () {

Route::get('customer/alltour',[CustomerController::class,'AllTour']);
Route::post('customer/join',[CustomerController::class,'joinTour']);



});

