<?php

use App\Http\Controllers\API\Instructorcontroller;
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



    Route::post('instructors/documents/{user}', [Instructorcontroller::class, 'documentUpload']);
    Route::post('instructors/register/firebase', [Instructorcontroller::class, 'firebaseRegister']);
    Route::post('instructors/login', [Instructorcontroller::class, 'instructorLogin']);
    Route::post('learners/login', [LearnerController::class, 'learnerLogin']);
    Route::post('learners/register/firebase', [LearnerController::class, 'firebaseRegister']);

    Route::post('instructors/update/{user}', [Instructorcontroller::class, 'update']);
    Route::apiResource('instructors', Instructorcontroller::class);
    Route::post('learners/update/{user}', [Learnercontroller::class, 'update']);
    Route::apiResource('learners', LearnerController::class);

    Route::post('instructors/profile', [Instructorcontroller::class, 'instructorProfile']);
    Route::post('learners/profile', [LearnerController::class, 'learnerProfile']);


    Route::post('instructors/rate/{user}', [Instructorcontroller::class, 'rate']);

    Route::apiResource('sessions', SessionController::class);
    Route::post('sessions/{session}/complete', [SessionController::class, 'markCompleted']);   // Mark as completed
    Route::post('sessions/{session}/reject', [SessionController::class, 'markRejected']);   // Mark as rejected
    Route::post('sessions/{session}/cancel', [SessionController::class, 'markCanceled']); // Mark as canceled
    Route::post('sessions/{session}/rate', [SessionController::class, 'rate']);

    Route::apiResource('packages', PackageController::class);

    // Standard CRUD for requests can't be made with apiResource due to custom methods
    Route::prefix('requests')->group(function () {
        Route::get('/', [RequestsController::class, 'index']);
        Route::post('/', [RequestsController::class, 'store']);
        Route::get('{courseRequest}', [RequestsController::class, 'show']);
        Route::put('{courseRequest}', [RequestsController::class, 'update']);
        Route::patch('{courseRequest}', [RequestsController::class, 'update']);
        Route::delete('{courseRequest}', [RequestsController::class, 'destroy']);
        Route::post('sessions', [SessionController::class, 'getByRequestId']);

    });


    Route::post('requests/instructor', [RequestsController::class, 'InstructorRequests']);

    Route::post('requests/learner', [RequestsController::class, 'LearnerRequests']);

    Route::get('requests/general', [RequestsController::class, 'general']);

    Route::post('requests/claim/{courseRequest}', [RequestsController::class, 'claim']);
    Route::post('requests/accept/{courseRequest}', [RequestsController::class, 'accept']);

    Route::middleware('auth:sanctum')->group(function () {});
});
