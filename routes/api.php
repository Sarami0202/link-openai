<?php

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

Route::post('/Conversion', 'App\Http\Controllers\ConversionController@Conversion');
Route::post('/onConversion', 'App\Http\Controllers\ConversionController@onConversion');
Route::post('/lastConversion', 'App\Http\Controllers\ConversionController@lastConversion');
Route::post('/CreateConversation', 'App\Http\Controllers\OpenaiController@CreateConversation');
Route::post('/lastConversation', 'App\Http\Controllers\OpenaiController@lastConversation');
Route::post('/onConversation', 'App\Http\Controllers\OpenaiController@onConversation');
Route::post('/CreateQuestion', 'App\Http\Controllers\OpenaiController@CreateQuestion');
Route::post('/CreateTranslation', 'App\Http\Controllers\OpenaiController@CreateTranslation');
Route::post('/CreateHint', 'App\Http\Controllers\OpenaiController@CreateHint');
Route::post('/CreateAnswer', 'App\Http\Controllers\OpenaiController@CreateAnswer');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
