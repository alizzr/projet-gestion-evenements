<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // <-- Ajoutez cette ligne

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route d'inscription
Route::post('/register', [AuthController::class, 'register']); // <-- Ajoutez cette ligne

// Route par défaut (gardez-la pour l'instant)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});