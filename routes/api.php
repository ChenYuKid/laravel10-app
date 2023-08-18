<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Traits\ApiResponse;
use App\Http\Controllers\TestController;

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

    Route::get('test1', [TestController::class, 'test1'])->name('test.test1');
    Route::get('test2', [TestController::class, 'test2'])->name('test.test2');
    Route::get('test3', [TestController::class, 'test3'])->name('test.test3');
    Route::get('test4', [TestController::class, 'test4'])->name('test.test4');
});
