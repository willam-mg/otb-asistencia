<?php

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

// Authentication
Route::prefix('auth')->group(function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/signup', 'AuthController@signup');
});

// Residente
Route::group(['prefix' => 'residentes'], function () {
    Route::get('/', 'ResidenteController@search');
    Route::post('create', 'ResidenteController@store');
    Route::put('update/{id}', 'ResidenteController@update');
    Route::get('show/{id}', 'ResidenteController@show');
    Route::delete('delete/{id}', 'ResidenteController@destroy');
});

// Inquilino
Route::group(['prefix' => 'inquilinos'], function () {
    Route::get('/', 'ResidenteController@search');
    Route::post('create', 'ResidenteController@store');
    Route::put('update/{id}', 'ResidenteController@update');
    Route::get('show/{id}', 'ResidenteController@show');
    Route::delete('delete/{id}', 'ResidenteController@destroy');
});
