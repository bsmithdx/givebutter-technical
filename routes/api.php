<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Contact\EmailController;
use App\Http\Controllers\Contact\PhoneController;
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
//Add route to add email for an existing contact
Route::patch('/contacts/{contactId}/emails', [EmailController::class, 'update']);
//Add route to remove email from an existing contact
Route::delete('/contacts/{contactId}/emails', [EmailController::class, 'destroy']);

//Add route to set phone numbers for an existing contact
Route::post('/contacts/{contactId}/phone-numbers', [PhoneController::class, 'store']);
//Add route to add phone number for an existing contact
Route::patch('/contacts/{contactId}/phone-numbers', [PhoneController::class, 'update']);
//Add route to remove phone number from an existing contact
Route::delete('/contacts/{contactId}/phone-numbers', [PhoneController::class, 'destroy']);

//Route::get('/contacts/{contact}/phone_numbers', [ContactController::class, 'store']);

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
