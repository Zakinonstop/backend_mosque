<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\MuwaqifController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\Api\SettingCertificateController;
use App\Http\Controllers\Api\SettingPluginWordpressController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::resource('products', ProductController::class);
  Route::resource('muwaqifs', MuwaqifController::class);
  Route::resource('transactions', TransactionController::class);
  Route::resource('roles', RoleController::class);
  Route::resource('visitors', VisitorController::class);
  Route::resource('setting-certificate', SettingCertificateController::class);
  Route::resource('setting-plugin-wordpress', SettingPluginWordpressController::class);

  Route::get('target-brick', [SettingPluginWordpressController::class, 'getTargetBrick']);
  Route::get('total-muwaqif', [MuwaqifController::class, 'getTotalMuwaqif']);
});