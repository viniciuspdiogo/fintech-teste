<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_user_can_register(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Teste',
            'email' => 'teste@example.com',
            'password' => 'Senha@12345',
        ])->assertCreated()
            ->assertJsonStructure(['data' => ['user', 'token']]);

        $this->assertDatabaseHas('users', ['email' => 'teste@example.com']);
    }

    /**
     * @return void
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['password' => 'Senha@12345']);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Senha@12345',
        ])->assertOk()
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    /**
     * @return void
     */
    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'senha-errada',
        ])->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('teste')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
