<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class UsuariosControllerTest extends TestCase
{
    public function test_pode_listar_usuarios_com_tipos_corretos()
    {
        $response = $this->getJson('/api/usuarios');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(2) // Verifica se retornou 2 usuários (conforme o mock)
                     ->each(fn (AssertableJson $json) =>
                        $json->whereAllType([
                            'id' => 'integer',
                            'nome' => 'string',
                            'email' => 'string',
                            'telefone' => 'string',
                            'tipo' => 'string',
                            'empresa' => 'array|null',
                            'ativo' => 'boolean',
                            'criadoEm' => 'string',
                            'atualizadoEm' => 'string'
                        ])
                     )
            );
    }

    public function test_pode_criar_usuario()
    {
        $payload = ['nome' => 'Novo Usuário', 'email' => 'novo@email.com'];
        $response = $this->postJson('/api/usuarios', $payload);
        
        $response->assertStatus(201)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('message')
                         ->has('data', fn (AssertableJson $json) =>
                            $json->where('id', 3)
                                 ->whereType('nome', 'string')
                                 ->etc()
                         )
                 );
    }

    public function test_pode_ver_usuario()
    {
        $response = $this->getJson('/api/usuarios/1');
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->whereType('id', 'integer')
                         ->whereType('nome', 'string')
                         ->etc()
                 );
    }

    public function test_pode_atualizar_usuario()
    {
        $payload = ['nome' => 'Nome Atualizado'];
        $response = $this->putJson('/api/usuarios/1', $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Nome Atualizado');
    }

    public function test_pode_remover_usuario()
    {
        $response = $this->deleteJson('/api/usuarios/1');
        $response->assertStatus(200);
    }
}
