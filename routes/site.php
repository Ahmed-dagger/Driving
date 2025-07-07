<?php

use App\Http\Controllers\Frontend;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {
        Route::group(['as' => 'site.'], function () {
            Route::get('/', Frontend\FrontendController::class)->name('home');
            Route::post('/validate', [Frontend\FrontendController::class , 'validateCode'])->name('code.validate');
        });

        require __DIR__ . '/auth.php';
    },
);
