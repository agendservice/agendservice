<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class UsuariosControllerTest extends TestCase
{
    /**
     * Teste para verificar se a listagem de usuários retorna os tipos corretos
     */
    public function test_pode_listar_usuarios_com_tipos_corretos()
    {
        $response = $this->getJson('/api/usuarios');

        $response->assertStatus(200)
                    ->assertJson(fn (AssertableJson $json) =>
                        $json->each(fn (AssertableJson $json) =>
                            $json->whereType('id', 'integer')
                                ->whereType('nome', 'string')
                                ->whereType('email', 'string')
                                ->whereType('telefone', 'string|integer|null')
                                ->whereType('status', 'string')
                                ->etc()
                        )
                    );
    }

    public function test_pode_criar_usuario()
    {
        $payload = ['nome' => 'Novo Usuário', 'email' => 'usuarioteste18@email.com', 'password' => 'senha1234', 'telefone' => '123456789', 'status' => 'ativo'];
        $response = $this->postJson('/api/usuarios', $payload);
        
        $response->assertStatus(201)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('message')
                         ->has('data', fn (AssertableJson $json) =>
                            $json->whereType('nome', 'string')
                                 ->etc()
                         )
                 );
        
        return $response->json('data.id');
    }

    /**
     * @depends test_pode_criar_usuario
     */
    public function test_pode_ver_usuario($usuarioId)
    {
        $response = $this->getJson("/api/usuarios/{$usuarioId}");
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->whereType('id', 'integer')
                         ->whereType('nome', 'string')
                         ->etc()
                 );
        
        return $usuarioId;
    }

    /**
     * @depends test_pode_ver_usuario
     */
    public function test_pode_atualizar_usuario($usuarioId)
    {
        $payload = ['nome' => 'Nome Atualizado'];
        $response = $this->putJson("/api/usuarios/{$usuarioId}", $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Nome Atualizado');
        
        return $usuarioId;
    }

    /**
     * @depends test_pode_atualizar_usuario
     */
    public function test_pode_remover_usuario($usuarioId)
    {
        $response = $this->deleteJson("/api/usuarios/{$usuarioId}");
        $response->assertStatus(200);
    }
}
