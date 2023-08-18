<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Traits\ApiResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('test')->group(function() {
    Route::get('test', function() {
        echo 'test run';
    })->name('test.test');
});
