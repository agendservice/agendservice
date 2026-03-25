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
    }

    public function test_pode_remover_servico()
    {
        $servico = Servico::factory()->create();
        $response = $this->deleteJson("/api/servicos/{$servico->id}");
        $response->assertStatus(204);
    }
}
