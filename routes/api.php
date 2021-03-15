<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Contact\EmailController;
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
//Add route to set emails for an existing contact
Route::post('/contacts/{contactId}/emails', [EmailController::class, 'store']);

//Route::get('/contacts/{contact}/phone-numbers', [ContactController::class, 'store']);

//Add route to merge contacts
Route::get('/contacts/{contact}/merge', [ContactController::class, 'merge']);
//Add limited api routing for contacts resource
Route::apiResource('contacts', ContactController::class)->only([
    'store',
    'update',
]);

Route::any('{any}', function ($any) {
    return response()->json(['message' => 'resource not found'], 404);
});
