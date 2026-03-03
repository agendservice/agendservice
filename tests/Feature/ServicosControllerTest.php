<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class ServicosControllerTest extends TestCase
{
    public function test_pode_listar_servicos_com_tipos_corretos()
    {
        $response = $this->getJson('/api/servicos');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'nome' => 'string',
                        'descricao' => 'string',
                        'duracaoMinutos' => 'integer',
                        'preco' => 'double|integer',
                        'intervaloMinimoDias' => 'integer',
                        'empresa' => 'array',
                        'ativo' => 'boolean',
                        'criadoEm' => 'string',
                        'atualizadoEm' => 'string'
                    ])
                )
            );
    }

    public function test_pode_criar_servico()
    {
        $payload = ['nome' => 'Novo Serviço', 'preco' => 100.00];
        $response = $this->postJson('/api/servicos', $payload);
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

    public function test_pode_ver_servico()
    {
        $response = $this->getJson('/api/servicos/1');
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->whereType('id', 'integer')
                         ->whereType('nome', 'string')
                         ->whereType('preco', 'double|integer')
                         ->etc()
                 );
    }

    public function test_pode_atualizar_servico()
    {
        $payload = ['nome' => 'Serviço Atualizado'];
        $response = $this->putJson('/api/servicos/1', $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Serviço Atualizado');
    }

    public function test_pode_remover_servico()
    {
        $response = $this->deleteJson('/api/servicos/1');
        $response->assertStatus(200);
    }
}
