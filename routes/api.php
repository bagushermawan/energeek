<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SkillController;
use App\Http\Controllers\API\SkillSetController;
use App\Http\Controllers\API\CandidateController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/trashed', [JobController::class, 'trashed']);
Route::post('/jobs', [JobController::class, 'store']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::put('/jobs/{id}', [JobController::class, 'update']);
Route::delete('/jobs/{id}', [JobController::class, 'destroy']);

Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/trashed', [SkillController::class, 'trashed']);
Route::post('/skills', [SkillController::class, 'store']);
Route::get('/skills/{id}', [SkillController::class, 'show']);
Route::put('/skills/{id}', [SkillController::class, 'update']);
Route::delete('/skills/{id}', [SkillController::class, 'destroy']);

Route::get('/candidates', [CandidateController::class, 'index']);
Route::get('/candidates/trashed', [CandidateController::class, 'trashed']);
Route::post('/candidates', [CandidateController::class, 'store']);
Route::get('/candidates/{id}', [CandidateController::class, 'show']);
Route::put('/candidates/{id}', [CandidateController::class, 'update']);
Route::delete('/candidates/{id}', [CandidateController::class, 'destroy']);

Route::get('/skillsets', [SkillSetController::class, 'index']);
Route::post('/skillsets', [SkillSetController::class, 'store']);
Route::get('/skillsets/{id}', [SkillSetController::class, 'show']);
Route::put('/skillsets/{id}', [SkillSetController::class, 'update']);
Route::delete('/skillsets/{id}', [SkillSetController::class, 'destroy']);

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
