<?php

use Illuminate\Support\Facades\Route;

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

Route::controller(App\Http\Controllers\Api\V1\LotteryCodeController::class)
    ->prefix('lotteries')
    ->name('lottery.code')
    ->group(function () {
        Route::get('/codes', 'index')
            ->name('.index');
        Route::get('/{lottery}/codes', 'indexByLottery')
            ->name('.lottery.index');
        Route::get('/codes/{code}', 'show')
            ->name('.show');
        Route::post('/{lottery}/codes', 'store')
            ->name('.store');
        Route::put('/codes/{code}', 'update')
            ->name('.update');
        Route::delete('/codes/{code}', 'destroy')
            ->name('.destroy');
    });

Route::controller(App\Http\Controllers\Api\V1\SpecialCodeController::class)
    ->prefix('codes/special')
    ->name('code.special')
    ->group(function () {
        Route::get('/', 'index')
            ->name('.index');
        Route::get('/{code}', 'show')
            ->name('.show');
        Route::post('/', 'store')
            ->name('.store');
        Route::put('/{code}', 'update')
            ->name('.update');
        Route::delete('/{code}', 'destroy')
            ->name('.destroy');
    });

Route::controller(App\Http\Controllers\Api\V1\LotteryController::class)
    ->prefix('lotteries')
    ->name('lottery')
    ->group(function () {
        Route::get('/', 'index')
            ->name('.index');
        Route::get('/{lottery}', 'show')
            ->name('.show');
        Route::post('/', 'store')
            ->name('.store');
        Route::put('/{lottery}', 'update')
            ->name('.update');
        Route::delete('/{lottery}', 'destroy')
            ->name('.destroy');
    });

Route::controller(App\Http\Controllers\Api\V1\CodeController::class)
    ->prefix('codes')
    ->name('code')
    ->group(function () {
        Route::get('/', 'index')
            ->name('.index');
        Route::get('/{code}', 'show')
            ->name('.show');
        Route::post('/', 'store')
            ->name('.store');
        Route::put('/{code}', 'update')
            ->name('.update');
        Route::delete('/{code}', 'destroy')
            ->name('.destroy');
    });
