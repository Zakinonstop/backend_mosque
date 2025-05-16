<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\MuwaqifController;
use App\Http\Controllers\Api\TransactionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::resource('posts', PostController::class);
  Route::resource('products', ProductController::class);
  Route::resource('muwaqifs', MuwaqifController::class);
  Route::resource('transactions', TransactionController::class);
});