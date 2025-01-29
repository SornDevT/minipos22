<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StoreController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => 'auth:api'],
    function(){
        Route::get('category',[CategoryController::class,'index']);
        Route::post('category/add',[CategoryController::class,'add']);
        Route::get('category/edit/{id}',[CategoryController::class,'edit']);
        Route::post('category/update/{id}',[CategoryController::class,'update']);
        Route::delete('category/delete/{id}',[CategoryController::class,'delete']);
    });

    Route::group(['middleware' => 'auth:api'],
    function(){
        Route::get('product',[StoreController::class,'index']);
        Route::post('product/add',[StoreController::class,'add']);
        Route::get('product/edit/{id}',[StoreController::class,'edit']);
        Route::post('product/update/{id}',[StoreController::class,'update']);
        Route::delete('product/delete/{id}',[StoreController::class,'delete']);
    });