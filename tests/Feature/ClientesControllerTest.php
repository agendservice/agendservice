<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_pode_listar_clientes()
    {
        $usuario = Usuario::factory()->create();
        Cliente::factory()->count(3)->create(['usuario_id' => $usuario->id]);

        $response = $this->getJson('/api/clientes');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data')
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'nome',
                             'email',
                             'telefone',
                             'usuario' => [
                                 'id',
                                 'nome',
                                 'email',
                             ]
                         ]
                     ]
                 ]);
    }

    /** @test */
    public function test_pode_criar_cliente_com_dados_validos()
    {
        $userData = Usuario::factory()->create();

        $clientData = [
            'usuario_id' => $userData->id,
            'nome' => 'Novo Cliente',
            'email' => 'novo.cliente@example.com',
            'telefone' => '11987654321',
        ];

        $response = $this->postJson('/api/clientes', $clientData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nome' => 'Novo Cliente',
                     'email' => 'novo.cliente@example.com',
                 ]);

        $this->assertDatabaseHas('clientes', [
            'email' => 'novo.cliente@example.com',
        ]);
    }

    /** @test */
    public function test_nao_pode_criar_cliente_com_dados_invalidos()
    {
        $clientData = [
            'nome' => 'Cliente Invalido',
            'telefone' => '11987654321',
        ];

        $response = $this->postJson('/api/clientes', $clientData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function test_nao_pode_criar_cliente_com_email_duplicado()
    {
        Cliente::factory()->create(['email' => 'existente@example.com']);

        $clientData = [
            'nome' => 'Cliente Duplicado',
            'email' => 'existente@example.com',
            'telefone' => '11999999999',
        ];

        $response = $this->postJson('/api/clientes', $clientData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function test_pode_atualizar_cliente_com_dados_validos()
    {
        $cliente = Cliente::factory()->create();
        $novosDados = [
            'nome' => 'Cliente Atualizado',
            'email' => 'atualizado@example.com',
            'telefone' => '11987654322',
        ];

        $response = $this->putJson("/api/clientes/{$cliente->id}", $novosDados);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nome' => 'Cliente Atualizado',
                     'email' => 'atualizado@example.com',
                 ]);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'email' => 'atualizado@example.com',
        ]);
    }

    /** @test */
    public function test_pode_atualizar_cliente_sem_validacao_de_email()
    {
        $cliente = Cliente::factory()->create();
        $novosDados = [
            'nome' => 'Cliente Atualizado',
            'email' => 'email-invalido',
            'telefone' => '11987654322',
        ];

        $response = $this->putJson("/api/clientes/{$cliente->id}", $novosDados);

        $response->assertStatus(200)
                 ->assertJsonPath('data.email', 'email-invalido');
    }

    /** @test */
    public function test_nao_pode_atualizar_cliente_inexistente()
    {
        $novosDados = [
            'nome' => 'Cliente Atualizado',
            'email' => 'atualizado@example.com',
            'telefone' => '11987654322',
        ];

        $response = $this->putJson("/api/clientes/999", $novosDados);

        $response->assertStatus(404);
    }

    /** @test */
    public function test_pode_ver_cliente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->getJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $cliente->id);
    }

    /** @test */
    public function test_pode_remover_cliente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->deleteJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    /** @test */
    public function test_pode_ver_historico_do_cliente()
    {
        $empresa = Empresa::factory()->create();
        $funcionario = Funcionario::factory()->create(['empresa_id' => $empresa->id]);
        $servico = Servico::factory()->create(['empresa_id' => $empresa->id]);
        $cliente = Cliente::factory()->create();
        $agendamento = Agendamento::factory()->create([
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
        ]);

        $response = $this->getJson("/api/clientes/{$cliente->id}/historico");

        $response->assertStatus(200)
                 ->assertJsonPath('data.0.id', $agendamento->id)
                 ->assertJsonPath('data.0.cliente_id', $cliente->id);
    }
}
