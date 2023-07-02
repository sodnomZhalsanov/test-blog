<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('register', [AuthController::class,'register']);


});

Route::group([

    'namespace' => 'Post',
    'middleware' => 'jwt.auth'

], function () {
    Route::get('posts', [UserController::class, 'showPosts']);
    Route::get('posts/{id}', [UserController::class, 'showPost']);
    Route::post('create-post', [UserController::class, 'createPost']);
    Route::post('create-category', [UserController::class, 'createCategory']);
    Route::post('update-post/{id}', [UserController::class, 'updatePost']);
    Route::get('delete-post/{id}', [UserController::class, 'deletePost']);
    Route::get('posts/{id}/comment', [UserController::class, 'comment']);
});
