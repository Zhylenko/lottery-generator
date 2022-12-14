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

Route::controller(App\Http\Controllers\Api\V1\LotteryController::class)
    ->prefix('lotteries')
    ->name('lottery')
    ->group(function () {

        Route::get('/', 'index')
            ->name('.index');
        Route::get('/{lottery}', 'show')
            ->whereNumber('lottery')
            ->name('.show');
        Route::post('/', 'store')
            ->name('.store');
        Route::put('/{lottery}', 'update')
            ->whereNumber('lottery')
            ->name('.update');
        Route::delete('/{lottery}', 'destroy')
            ->whereNumber('lottery')
            ->name('.destroy');

        Route::controller(App\Http\Controllers\Api\V1\LotteryCodeController::class)
            ->name('.code')
            ->group(function () {

                Route::get('/codes', 'index')
                    ->name('.index');
                Route::get('/{lottery}/codes', 'indexByLottery')
                    ->whereNumber('lottery')
                    ->name('.lottery.index');
                Route::get('/codes/{code}', 'show')
                    ->whereNumber('code')
                    ->name('.show');
                Route::post('/{lottery}/codes', 'store')
                    ->whereNumber('lottery')
                    ->name('.store');
                Route::put('/codes/{code}', 'update')
                    ->whereNumber('code')
                    ->name('.update');
                Route::delete('/codes/{code}', 'destroy')
                    ->whereNumber('code')
                    ->name('.destroy');

                Route::controller(App\Http\Controllers\Api\V1\LotteryCodeSpecialController::class)
                    ->name('.special')
                    ->group(function () {

                        Route::get('/codes/special', 'index')
                            ->name('.index');
                        Route::get('/{lottery}/codes/special', 'indexByLottery')
                            ->whereNumber('lottery')
                            ->name('.lottery.index');
                        Route::get('/codes/{code}/special', 'show')
                            ->whereNumber('code')
                            ->name('.show');
                        Route::post('/{lottery}/codes/special', 'store')
                            ->whereNumber('lottery')
                            ->name('.store');
                        Route::put('/codes/{code}/special', 'update')
                            ->whereNumber('code')
                            ->name('.update');
                        Route::delete('/codes/{code}/special', 'destroy')
                            ->whereNumber('code')
                            ->name('.destroy');
                    });

                Route::controller(App\Http\Controllers\Api\V1\LotteryCodeGeneratedController::class)
                    ->name('.generated')
                    ->group(function () {

                        Route::post('/codes/generated', 'generate')
                            ->name('.generate');
                    });
            });
    });

Route::controller(App\Http\Controllers\Api\V1\CodeController::class)
    ->prefix('codes')
    ->name('code')
    ->group(function () {

        Route::get('/', 'index')
            ->name('.index');
        Route::get('/{code}', 'show')
            ->whereNumber('code')
            ->name('.show');
        Route::post('/', 'store')
            ->name('.store');
        Route::put('/{code}', 'update')
            ->whereNumber('code')
            ->name('.update');
        Route::delete('/{code}', 'destroy')
            ->whereNumber('code')
            ->name('.destroy');

        Route::controller(App\Http\Controllers\Api\V1\SpecialCodeController::class)
            ->prefix('special')
            ->name('.special')
            ->group(function () {

                Route::get('/', 'index')
                    ->name('.index');
                Route::get('/{code}', 'show')
                    ->whereNumber('code')
                    ->name('.show');
                Route::post('/', 'store')
                    ->name('.store');
                Route::put('/{code}', 'update')
                    ->whereNumber('code')
                    ->name('.update');
                Route::delete('/{code}', 'destroy')
                    ->whereNumber('code')
                    ->name('.destroy');
            });
    });
