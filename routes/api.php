<?php

use Illuminate\Http\Request;
use \App\Http\Controllers\ContactController;
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

//Route::get('/contacts/{contact}/emails', [ContactController::class, 'emails']);

//Route::get('/contacts/{contact}/phone-numbers', [ContactController::class, 'phoneNumbers']);

Route::get('/contacts/{contact}/merge', [ContactController::class, 'merge']);

Route::apiResource('contacts',ContactController::class)->only([
    'store',
    'show',
    'update',
    'destroy',
]);

Route::any('{any}', function ($any) {
    return response()->json(['message' => 'resource not found'], 404);
});
