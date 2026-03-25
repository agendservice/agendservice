<?php

namespace Tests\Feature;

use Tests\TestCase;
use Carbon\Carbon;
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
        $servico = Servico::factory()->create(['duracao_minutos' => 60]);

        $inicio = Carbon::now()->addDays(2)->setTime(10, 0, 0);
        $fim = $inicio->copy()->addMinutes($servico->duracao_minutos);

        $payload = [
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => $inicio->format('Y-m-d H:i:s'),
        ];
        $response = $this->postJson('/api/agendamentos', $payload);
        $response->assertStatus(201)
                 ->assertJsonPath('data.cliente_id', $cliente->id)
                 ->assertJsonPath('data.funcionario_id', $funcionario->id)
                 ->assertJsonPath('data.servico_id', $servico->id)
                 ->assertJsonPath('data.status', 'pendente');

        $this->assertDatabaseHas('agendamentos', [
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => $inicio->format('Y-m-d H:i:s'),
            'data_hora_fim' => $fim->format('Y-m-d H:i:s'),
            'status' => 'pendente',
        ]);
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
        $this->assertDatabaseMissing('agendamentos', ['id' => $agendamento->id]);
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
            'data_hora_inicio' => "$amanha 14:30:00",
        ];

        $response = $this->postJson('/api/agendamentos', $payload);
        $response->assertStatus(422)
                 ->assertJsonPath('message', 'O funcionário já possui um agendamento neste horário.');
    }

    public function test_nao_pode_criar_agendamento_com_dados_invalidos()
    {
        $response = $this->postJson('/api/agendamentos', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'cliente_id',
                     'funcionario_id',
                     'servico_id',
                     'data_hora_inicio',
                 ]);
    }

    public function test_nao_pode_criar_agendamento_no_passado()
    {
        $cliente = Cliente::factory()->create();
        $funcionario = Funcionario::factory()->create();
        $servico = Servico::factory()->create();

        $payload = [
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/agendamentos', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['data_hora_inicio']);
    }

    public function test_nao_pode_ver_agendamento_inexistente()
    {
        $response = $this->getJson('/api/agendamentos/999999');

        $response->assertStatus(404);
    }

    public function test_nao_pode_remover_agendamento_inexistente()
    {
        $response = $this->deleteJson('/api/agendamentos/999999');

        $response->assertStatus(404);
    }
}
