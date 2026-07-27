<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/check-promo', function (Illuminate\Http\Request $request) {
    $code = $request->query('code');
    $total = floatval($request->query('total', 0));
    $promo = \App\Models\Promo::where('code', $code)->first();
    if (!$promo || !$promo->isValid()) return response()->json(['valid' => false, 'message' => 'Kode promo tidak valid atau kadaluarsa']);
    $discount = $promo->discount_type === 'percent' ? ($total * ($promo->discount_amount / 100)) : $promo->discount_amount;
    return response()->json(['valid' => true, 'discount' => round($discount)]);
});