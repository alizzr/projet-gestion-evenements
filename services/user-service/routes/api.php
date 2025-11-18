<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route d'inscription
Route::post('/register', [AuthController::class, 'register']);
// Route de connexion (retourne un token simulé)
Route::post('/login', [AuthController::class, 'login']);
// Profil utilisateur (protégé)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});