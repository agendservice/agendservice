<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class AgendamentosControllerTest extends TestCase
{
    public function test_pode_listar_agendamentos_com_tipos_corretos()
    {
        $response = $this->getJson('/api/agendamentos');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'cliente' => 'array',
                        'servico' => 'array',
                        'dataHoraInicio' => 'string',
                        'dataHoraFim' => 'string',
                        'status' => 'string',
                        'observacao' => 'string',
                        'criadoEm' => 'string',
                        'atualizadoEm' => 'string'
                    ])
                    ->has('servico.empresa')
                )
            );
    }

    public function test_pode_criar_agendamento()
    {
        $payload = ['servico_id' => 1, 'dataHoraInicio' => '2026-03-10 14:00:00'];
        $response = $this->postJson('/api/agendamentos', $payload);
        $response->assertStatus(201);
    }

    public function test_pode_ver_agendamento()
    {
        $response = $this->getJson('/api/agendamentos/101');
        $response->assertStatus(200);
    }

    public function test_pode_atualizar_agendamento()
    {
        $payload = ['status' => 'confirmado'];
        $response = $this->putJson('/api/agendamentos/101', $payload);
        $response->assertStatus(200);
    }

    public function test_pode_remover_agendamento()
    {
        $response = $this->deleteJson('/api/agendamentos/101');
        $response->assertStatus(200);
    }
}
