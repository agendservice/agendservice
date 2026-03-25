<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Servico;
use App\Models\Empresa;

class ServicosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_servicos_com_tipos_corretos()
    {
        Servico::factory()->create();

        $response = $this->getJson('/api/servicos');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 1, fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'empresa_id' => 'integer',
                        'nome' => 'string',
                        'descricao' => 'string|null',
                        'duracao_minutos' => 'integer',
                        'preco' => 'double|integer|string',
                        'criado_em' => 'string',
                        'atualizado_em' => 'string'
                    ])->etc()
                )
            );
    }

    public function test_pode_criar_servico()
    {
        $empresa = Empresa::factory()->create();
        $payload = [
            'empresa_id' => $empresa->id,
            'nome' => 'Novo Serviço',
            'duracao_minutos' => 45,
            'preco' => 100.00
        ];
        $response = $this->postJson('/api/servicos', $payload);
        $response->assertStatus(201)
                 ->assertJsonPath('data.nome', 'Novo Serviço');

        $this->assertDatabaseHas('servicos', [
            'empresa_id' => $empresa->id,
            'nome' => 'Novo Serviço',
            'duracao_minutos' => 45,
        ]);
    }

    public function test_pode_ver_servico()
    {
        $servico = Servico::factory()->create();
        $response = $this->getJson("/api/servicos/{$servico->id}");
        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $servico->id);
    }

    public function test_pode_atualizar_servico()
    {
        $servico = Servico::factory()->create();
        $payload = ['nome' => 'Serviço Atualizado'];
        $response = $this->putJson("/api/servicos/{$servico->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Serviço Atualizado');

        $this->assertDatabaseHas('servicos', [
            'id' => $servico->id,
            'nome' => 'Serviço Atualizado',
        ]);
    }

    public function test_pode_remover_servico()
    {
        $servico = Servico::factory()->create();
        $response = $this->deleteJson("/api/servicos/{$servico->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('servicos', ['id' => $servico->id]);
    }

    public function test_nao_pode_criar_servico_com_dados_invalidos()
    {
        $response = $this->postJson('/api/servicos', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'empresa_id',
                     'nome',
                     'duracao_minutos',
                     'preco',
                 ]);
    }

    public function test_nao_pode_criar_servico_com_duracao_invalida()
    {
        $empresa = Empresa::factory()->create();

        $payload = [
            'empresa_id' => $empresa->id,
            'nome' => 'Novo Serviço',
            'duracao_minutos' => 0,
            'preco' => 100.00,
        ];

        $response = $this->postJson('/api/servicos', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['duracao_minutos']);
    }

    public function test_nao_pode_criar_servico_com_preco_invalido()
    {
        $empresa = Empresa::factory()->create();

        $payload = [
            'empresa_id' => $empresa->id,
            'nome' => 'Novo Serviço',
            'duracao_minutos' => 30,
            'preco' => -1,
        ];

        $response = $this->postJson('/api/servicos', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['preco']);
    }

    public function test_nao_pode_ver_servico_inexistente()
    {
        $response = $this->getJson('/api/servicos/999999');

        $response->assertStatus(404);
    }

    public function test_nao_pode_atualizar_servico_inexistente()
    {
        $response = $this->putJson('/api/servicos/999999', ['nome' => 'Serviço Atualizado']);

        $response->assertStatus(404);
    }

    public function test_nao_pode_remover_servico_inexistente()
    {
        $response = $this->deleteJson('/api/servicos/999999');

        $response->assertStatus(404);
    }
}
