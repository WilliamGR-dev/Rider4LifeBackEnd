<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\RoadsController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->patch('/profile', [AuthController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->patch('/password', [AuthController::class, 'updatePassword']);
Route::post('/recoverypassword', [AuthController::class, 'recoveryPassword']);

Route::middleware('auth:sanctum')->post('/news', [NewsController::class, 'create']);
Route::middleware('auth:sanctum')->get('/news', [NewsController::class, 'getAllNews']);
Route::middleware('auth:sanctum')->get('/news/{id}', [NewsController::class, 'getNews']);
Route::middleware('auth:sanctum')->get('/news/user/{id}', [NewsController::class, 'getNewsByUserId']);
Route::middleware('auth:sanctum')->put('/news/{id}', [NewsController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/news/{id}', [NewsController::class, 'delete']);

Route::middleware('auth:sanctum')->post('/likes/{id}', [LikesController::class, 'create']);
Route::middleware('auth:sanctum')->get('/likes/{id}', [LikesController::class, 'getLikesByNewsId']);
Route::middleware('auth:sanctum')->delete('/likes/{id}', [LikesController::class, 'delete']);

Route::middleware('auth:sanctum')->post('/comments/{id}', [CommentsController::class, 'create']);
Route::middleware('auth:sanctum')->get('/comments/{id}', [CommentsController::class, 'getCommentsByNewsId']);
Route::middleware('auth:sanctum')->delete('/comments/{id}', [CommentsController::class, 'delete']);

Route::middleware('auth:sanctum')->post('/roads', [RoadsController::class, 'create']);
Route::middleware('auth:sanctum')->get('/roads', [RoadsController::class, 'getAllRoads']);
Route::middleware('auth:sanctum')->get('/roads/{id}', [RoadsController::class, 'getRoad']);
Route::middleware('auth:sanctum')->post('/roads/members/{id}', [RoadsController::class, 'joinRoad']);
Route::middleware('auth:sanctum')->get('/roads/members/{id}', [RoadsController::class, 'getAllMembersRoads']);
Route::middleware('auth:sanctum')->delete('/roads/members/{id}', [RoadsController::class, 'quitRoad']);


