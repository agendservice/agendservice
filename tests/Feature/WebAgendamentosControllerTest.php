<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebAgendamentosControllerTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): Usuario
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_rotas_web_de_agendamento_exigem_autenticacao()
    {
        $this->post('/agendamento', [])->assertStatus(302);
    }

    public function test_buscar_agendamentos_retorna_paginacao_e_aplica_filtros_por_status_e_data()
    {
        $this->autenticar();

        $hoje = Carbon::now()->addDays(2)->format('Y-m-d');

        Agendamento::factory()->create([
            'status' => 'pendente',
            'data_hora_inicio' => "{$hoje} 10:00:00",
            'data_hora_fim' => "{$hoje} 11:00:00",
        ]);

        Agendamento::factory()->create([
            'status' => 'confirmado',
            'data_hora_inicio' => "{$hoje} 15:00:00",
            'data_hora_fim' => "{$hoje} 16:00:00",
        ]);

        $response = $this->postJson('/agendamento', [
            'status' => 'pendente',
            'data_hora_inicio' => $hoje,
            'per_page' => 1,
            'page' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'pendente')
            ->assertJsonStructure(['meta' => ['links']]);
    }

    public function test_pode_cadastrar_atualizar_status_observacao_e_remover_agendamento_nas_rotas_web()
    {
        $this->autenticar();

        $cliente = Cliente::factory()->create();
        $funcionario = Funcionario::factory()->create();
        $servico = Servico::factory()->create(['duracao_minutos' => 30]);

        $inicio = Carbon::now()->addDays(3)->setTime(14, 0, 0)->format('Y-m-d H:i:s');

        $create = $this->postJson('/agendamento/cadastrar', [
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => $inicio,
            'observacao' => 'Primeiro atendimento',
        ]);

        $create->assertStatus(201);
        $id = $create->json('data.id');

        $this->putJson('/agendamento/atualizar', [
            'id' => $id,
            'status' => 'confirmado',
            'observacao' => 'Atualizado no painel',
        ])->assertStatus(200)
            ->assertJsonPath('data.status', 'confirmado')
            ->assertJsonPath('data.observacao', 'Atualizado no painel');

        $this->assertDatabaseHas('agendamentos', [
            'id' => $id,
            'status' => 'confirmado',
            'observacao' => 'Atualizado no painel',
        ]);

        $this->deleteJson('/agendamento/' . $id)->assertStatus(204);
        $this->assertDatabaseMissing('agendamentos', ['id' => $id]);
    }
}
