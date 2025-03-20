<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login'); // Redirige vers la page de connexion
});


Route::get('/install', [App\Http\Controllers\SetupController::class, 'index']);
Route::post('/install', [App\Http\Controllers\SetupController::class, 'setup']);