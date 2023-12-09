<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Models\Comment;

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

Route::group(['prefix' => 'auth'], function(){
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/log-out', [AuthController::class, 'logout'])->name('log-out');
        Route::get('/user', [AuthController::class, 'user'])->name('user');
    });
});


Route::group(['prefix' => 'posts'], function(){
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{postId}', [PostController::class, 'show']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{postId}', [PostController::class, 'update']);
        Route::delete('/{postId}', [PostController::class, 'destroy']);

        Route::post('/{postId}/like', [PostController::class, 'like']);
        Route::post('/{postId}/dislike', [PostController::class, 'dislike']);

        Route::post('/{postId}/comments', [CommentController::class, 'store']);
        Route::put('/comments/{commentId}', [CommentController::class, 'update']);
        Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);
    });
});

