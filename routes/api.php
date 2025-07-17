<?php

use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\API\Learnercontroller;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\RequestsController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\Usercontroller;
use Illuminate\Contracts\Session\Session;
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


Route::group(['name' => 'App\Http\Controllers\Api'], function () {

    Route::apiResource('learners', LearnerController::class);
    Route::post('learners/login/firebase', [LearnerController::class, 'firebaseLogin']);


    Route::apiResource('instructors', InstructorController::class);
    Route::post('instructors/documents/{user}', [InstructorController::class, 'documentUpload']);
    Route::post('instructors/login/firebase', [InstructorController::class, 'firebaseLogin']);

    Route::apiResource('sessions', SessionController::class);

    Route::apiResource('packages', PackageController::class);

    Route::apiResource('requests', RequestsController::class);

    Route::post('requests/instructor', [RequestsController::class, 'InstructorRequests']);

    Route::get('requests/general', [RequestsController::class, 'general']);
    Route::post('requests/{request}/claim', [RequestsController::class, 'claim']);




});
