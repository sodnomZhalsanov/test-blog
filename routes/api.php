<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserDataController;
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


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('register', [AuthController::class,'register']);


});

Route::group([

    'namespace' => 'Post',
    'middleware' => 'jwt.auth'

], function () {
    Route::get('posts', [PostController::class, 'showPosts']);
    Route::get('posts/{id}', [PostController::class, 'showPost']);
    Route::post('posts/create-post', [PostController::class, 'createPost']);
    Route::post('posts/create-category', [PostController::class, 'createCategory']);
    Route::put('posts/{id}', [PostController::class, 'updatePost']);
    Route::delete('posts/{id}', [PostController::class, 'deletePost']);
    Route::get('posts/{id}/like', [PostController::class, 'like']);
    Route::post('posts/{id}/comment', [PostController::class, 'comment']);
});

Route::group([

    'namespace' => 'User',
    'middleware' => 'jwt.auth'

], function () {
    Route::get('user', [UserDataController::class, 'showUserData']);
    Route::put('user', [UserDataController::class, 'editUserData']);
    Route::get('user/liked', [UserDataController::class, 'showLikedPosts']);
    Route::get('user/commented', [UserDataController::class, 'showCommentedPosts']);
});
