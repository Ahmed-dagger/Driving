<?php

use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\API\Learnercontroller;
use App\Http\Controllers\API\Usercontroller;
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

    Route::apiResource('instructors', InstructorController::class);

    Route::post('learners/login/firebase', [LearnerController::class, 'firebaseLogin']);


    // //----------- Google Sign-In Routes -------------//


    // Route::post('/google/token-login', [RegisterController::class, 'googleTokenLogin']);

    //----------- Google Sign-In Routes -------------//
});
