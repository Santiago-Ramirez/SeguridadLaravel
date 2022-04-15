<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('guest')->group(function (){
    Route::get('login', function () {
        return view('login-view');
    })->name('login');
});

Route::get('/register', function () {
    return view('register-view');
})->middleware('auth');

Route::get('home', [AuthController::class, 'index2'])->middleware('auth');

Route::get('code/{email}', function ($email) {
     return view('code-view')->with('email', $email);
    // return "entro aqui";
})->name('code');

Route::fallback(function () {
    return redirect('/login');
});

 Route::post('/login', [AuthController::class, 'login']);
 Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
 Route::post('/register', [AuthController::class, 'register'])->middleware('auth');
Route::post('code/login-with-code', [AuthController::class, 'loginWithCode']);



Route::post('/NomameRafa/{id}', [AuthController::class, 'update']);

// Route::get('editarUser', function (){
//     return view('editar');
// });


Route::get('editarUser/{id}', [AuthController::class, 'vistaEditar']);


// Route::get('/register', function () {
//     return view('login')->with('user', Auth::user());
// })->middleware('auth');