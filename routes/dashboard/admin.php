<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {

            Route::get('dashboard', Dashboard\DashboardController::class)->name('dashboard');

            Route::resource('admins', Dashboard\AdminController::class);

            Route::delete('destroy/{id}', [Dashboard\AdminController::class, 'destroy'])->name('destroy');

            Route::post('resotre/{id}', [Dashboard\AdminController::class, 'restore'])->name('restore');

            Route::resource('codes', Dashboard\CodesController::class);

            Route::resource('learners', Dashboard\LearnerController::class);

            Route::post('learners/{id}/restore', [Dashboard\LearnerController::class, 'restore'])->name('learners.restore');

            Route::resource('packages', Dashboard\PackageController::class);

            Route::post('packages/{id}/restore', [Dashboard\PackageController::class, 'restore'])->name('packages.restore');

            Route::resource('instructors', Dashboard\InstructorController::class);

            Route::post('instructors/{id}/restore', [Dashboard\InstructorController::class, 'restore'])->name('instructors.restore');

            Route::get('/settings', [Dashboard\SettingController::class, 'index'])->name('settings');
            Route::post('/settings/store', [Dashboard\SettingController::class, 'store'])->name('settings.store');
        });




        require __DIR__ . '../../auth.php';
    }
);
