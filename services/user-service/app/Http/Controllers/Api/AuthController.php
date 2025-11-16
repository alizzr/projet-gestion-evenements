<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Gère l'inscription d'un nouvel utilisateur.
     */
    public function register(Request $request)
    {
        // 1. Validation (exactement ce que le test attend)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Création du Token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Réponse (exactement ce que le test attend)
        return response()->json([
            'status' => 'User registered successfully',
            'token' => $token,
        ], 201); // 201 = "Créé"
    }
}