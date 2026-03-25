<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Servico;

class AgendamentosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_agendamentos_com_tipos_corretos()
    {
        Agendamento::factory()->create();

        $response = $this->getJson('/api/agendamentos');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 1, fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'cliente_id' => 'integer',
                        'funcionario_id' => 'integer',
                        'servico_id' => 'integer',
                        'data_hora_inicio' => 'string',
                        'data_hora_fim' => 'string',
                        'status' => 'string',
                        'criado_em' => 'string',
                        'atualizado_em' => 'string'
                    ])->etc()
                )
            );
    }

    public function test_pode_criar_agendamento()
    {
        $cliente = Cliente::factory()->create();
        $funcionario = Funcionario::factory()->create();
        $servico = Servico::factory()->create();

        $payload = [
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => now()->addDay()->format('Y-m-d H:i:s'),
        ];
        $response = $this->postJson('/api/agendamentos', $payload);
        $response->assertStatus(201);
    }

    public function test_pode_ver_agendamento()
    {
        $agendamento = Agendamento::factory()->create();
        $response = $this->getJson("/api/agendamentos/{$agendamento->id}");
        $response->assertStatus(200);
    }

    public function test_pode_atualizar_agendamento()
    {
        $agendamento = Agendamento::factory()->create();
        $payload = ['status' => 'confirmado'];
        $response = $this->putJson("/api/agendamentos/{$agendamento->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.status', 'confirmado');
    }

    public function test_pode_remover_agendamento()
    {
        $agendamento = Agendamento::factory()->create();
        $response = $this->deleteJson("/api/agendamentos/{$agendamento->id}");
        $response->assertStatus(204);
    }

    public function test_nao_pode_criar_agendamento_com_sobreposicao()
    {
        $amanha = now()->addDay()->format('Y-m-d');
        
        $agendamentoExistente = Agendamento::factory()->create([
            'data_hora_inicio' => "$amanha 14:00:00",
            'data_hora_fim' => "$amanha 15:00:00",
        ]);

        $payload = [
            'cliente_id' => Cliente::factory()->create()->id,
            'funcionario_id' => $agendamentoExistente->funcionario_id,
            'servico_id' => Servico::factory()->create(['duracao_minutos' => 30])->id,
            'data_hora_inicio' => "$amanha 14:30:00", // Sobrepõe
        ];

        $response = $this->postJson('/api/agendamentos', $payload);
        $response->assertStatus(422)
                 ->assertJsonPath('message', 'O funcionário já possui um agendamento neste horário.');
    }
}
