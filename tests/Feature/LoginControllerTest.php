<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_realizar_login_com_credenciais_validas()
    {
        $usuario = Usuario::factory()->create([
            'email' => 'teste@email.com',
            'password' => Hash::make('senha123')
        ]);

        $payload = [
            'email' => 'teste@email.com',
            'password' => 'senha123'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->whereType('token', 'string')
                     ->has('usuario', fn (AssertableJson $json) =>
                        $json->where('id', $usuario->id)
                             ->where('nome', $usuario->nome)
                             ->where('email', $usuario->email)
                             ->etc()
                     )
            );
    }

    public function test_nao_pode_realizar_login_com_senha_incorreta()
    {
        Usuario::factory()->create([
            'email' => 'teste@email.com',
            'password' => Hash::make('senha123')
        ]);

        $payload = [
            'email' => 'teste@email.com',
            'password' => 'senha_errada'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422);
    }
}
