<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empresa;

class EmpresasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_empresas_com_tipos_corretos()
    {
        Empresa::factory()->create();

        $response = $this->getJson('/api/empresas');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 1, fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'nome' => 'string',
                        'cnpj' => 'string|null',
                        'endereco' => 'string|null',
                        'telefone' => 'string|null',
                        'criado_em' => 'string',
                        'atualizado_em' => 'string'
                    ])
                )
            );
    }

    public function test_pode_criar_empresa()
    {
        $payload = ['nome' => 'Nova Empresa', 'cnpj' => '12.345.678/0001-90'];
        $response = $this->postJson('/api/empresas', $payload);
        $response->assertStatus(201)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('data', fn (AssertableJson $json) =>
                        $json->whereType('id', 'integer')
                             ->where('nome', 'Nova Empresa')
                             ->etc()
                    )
                 );

        $this->assertDatabaseHas('empresas', [
            'nome' => 'Nova Empresa',
            'cnpj' => '12.345.678/0001-90',
        ]);
    }

    public function test_pode_ver_empresa()
    {
        $empresa = Empresa::factory()->create();
        $response = $this->getJson("/api/empresas/{$empresa->id}");
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('data', fn (AssertableJson $json) =>
                        $json->whereType('id', 'integer')
                             ->where('nome', $empresa->nome)
                             ->etc()
                    )
                 );
    }

    public function test_pode_atualizar_empresa()
    {
        $empresa = Empresa::factory()->create();
        $payload = ['nome' => 'Empresa Atualizada'];
        $response = $this->putJson("/api/empresas/{$empresa->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Empresa Atualizada');

        $this->assertDatabaseHas('empresas', [
            'id' => $empresa->id,
            'nome' => 'Empresa Atualizada',
        ]);
    }

    public function test_pode_remover_empresa()
    {
        $empresa = Empresa::factory()->create();
        $response = $this->deleteJson("/api/empresas/{$empresa->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('empresas', ['id' => $empresa->id]);
    }

    public function test_nao_pode_criar_empresa_sem_nome()
    {
        $response = $this->postJson('/api/empresas', ['cnpj' => '12.345.678/0001-90']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nome']);
    }

    public function test_nao_pode_ver_empresa_inexistente()
    {
        $response = $this->getJson('/api/empresas/999999');

        $response->assertStatus(404);
    }

    public function test_nao_pode_atualizar_empresa_inexistente()
    {
        $response = $this->putJson('/api/empresas/999999', ['nome' => 'Empresa Atualizada']);

        $response->assertStatus(404);
    }

    public function test_nao_pode_remover_empresa_inexistente()
    {
        $response = $this->deleteJson('/api/empresas/999999');

        $response->assertStatus(404);
    }
}
