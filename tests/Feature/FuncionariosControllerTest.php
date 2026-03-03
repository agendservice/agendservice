<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class FuncionariosControllerTest extends TestCase
{
    public function test_pode_listar_funcionarios_com_tipos_corretos()
    {
        $response = $this->getJson('/api/funcionarios');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'nome' => 'string',
                        'email' => 'string',
                        'empresa' => 'array',
                        'ativo' => 'boolean',
                        'criadoEm' => 'string',
                        'atualizadoEm' => 'string'
                    ])
                )
            );
    }

    public function test_pode_criar_funcionario()
    {
        $payload = ['nome' => 'Novo Funcionário', 'email' => 'funcionario@email.com'];
        $response = $this->postJson('/api/funcionarios', $payload);
        $response->assertStatus(201)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('message')
                         ->has('data', fn (AssertableJson $json) =>
                            $json->whereType('id', 'integer')
                                 ->etc()
                         )
                 );
    }

    public function test_pode_ver_funcionario()
    {
        $response = $this->getJson('/api/funcionarios/2');
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->whereType('id', 'integer')
                         ->whereType('nome', 'string')
                         ->etc()
                 );
    }

    public function test_pode_atualizar_funcionario()
    {
        $payload = ['nome' => 'Nome Atualizado'];
        $response = $this->putJson('/api/funcionarios/2', $payload);
        $response->assertStatus(200);
    }

    public function test_pode_remover_funcionario()
    {
        $response = $this->deleteJson('/api/funcionarios/2');
        $response->assertStatus(200);
    }
}
