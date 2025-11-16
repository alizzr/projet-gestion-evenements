<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
    // Cette ligne est MAGIQUE. Elle réinitialise la BDD pour chaque test.
    // Cela garantit que vos tests sont "propres" et indépendants.
    use RefreshDatabase;

    /**
     * Teste si un utilisateur peut s'inscrire avec succès.
     * @test
     */
    public function a_user_can_register_successfully()
    {
        // 1. Préparation (Données de test)
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // 2. Action (Appel de l'API)
        $response = $this->postJson('/api/register', $userData);

        // 3. Assertions (Vérifications)
        $response->assertStatus(201); // 201 = "Créé"

        // Vérifie que la réponse contient bien un token
        $response->assertJsonStructure([
            'status',
            'token',
        ]);

        // Vérifie que l'utilisateur a bien été créé dans la BDD
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }
}