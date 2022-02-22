<?php

use App\Http\Controllers\AuthController;
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
Route::post('/email', [AuthController::class, 'index']);
Route::post('send-verification-email', [AuthController::class, 'sendVerificationEmail']);
Route::get('verify-email', [AuthController::class, 'verifyEmail'])->name('verifyEmail');

include __DIR__ . '/api/v1/routes.php';


