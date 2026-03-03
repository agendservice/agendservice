<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class EmpresasControllerTest extends TestCase
{
    public function test_pode_listar_empresas_com_tipos_corretos()
    {
        $response = $this->getJson('/api/empresas');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'nome' => 'string',
                        'descricao' => 'string',
                        'telefone' => 'string',
                        'email' => 'string',
                        'endereco' => 'string',
                        'capacidadeSimultanea' => 'integer',
                        'ativo' => 'boolean',
                        'criadoEm' => 'string',
                        'atualizadoEm' => 'string'
                    ])
                )
            );
    }

    public function test_pode_criar_empresa()
    {
        $payload = ['nome' => 'Nova Empresa', 'email' => 'contato@empresa.com'];
        $response = $this->postJson('/api/empresas', $payload);
        $response->assertStatus(201)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('message')
                         ->has('data', fn (AssertableJson $json) =>
                            $json->whereType('id', 'integer')
                                 ->whereType('nome', 'string')
                                 ->etc()
                         )
                 );
    }

    public function test_pode_ver_empresa()
    {
        $response = $this->getJson('/api/empresas/1');
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->whereType('id', 'integer')
                         ->whereType('nome', 'string')
                         ->etc()
                 );
    }

    public function test_pode_atualizar_empresa()
    {
        $payload = ['nome' => 'Empresa Atualizada'];
        $response = $this->putJson('/api/empresas/1', $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Empresa Atualizada');
    }

    public function test_pode_remover_empresa()
    {
        $response = $this->deleteJson('/api/empresas/1');
        $response->assertStatus(200);
    }
}
