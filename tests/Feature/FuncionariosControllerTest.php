<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Funcionario;
use App\Models\Empresa;

class FuncionariosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_funcionarios_com_tipos_corretos()
    {
        Funcionario::factory()->create();

        $response = $this->getJson('/api/funcionarios');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 1, fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'empresa_id' => 'integer',
                        'usuario_id' => 'integer|null',
                        'nome' => 'string',
                        'especialidade' => 'string|null',
                        'criado_em' => 'string',
                        'atualizado_em' => 'string'
                    ])->etc()
                )
            );
    }

    public function test_pode_criar_funcionario()
    {
        $empresa = Empresa::factory()->create();
        $payload = [
            'empresa_id' => $empresa->id,
            'nome' => 'Novo Funcionário',
            'especialidade' => 'Barbeiro'
        ];
        $response = $this->postJson('/api/funcionarios', $payload);
        $response->assertStatus(201)
                 ->assertJsonPath('data.nome', 'Novo Funcionário');

        $this->assertDatabaseHas('funcionarios', [
            'empresa_id' => $empresa->id,
            'nome' => 'Novo Funcionário',
            'especialidade' => 'Barbeiro',
        ]);
    }

    public function test_pode_ver_funcionario()
    {
        $funcionario = Funcionario::factory()->create();
        $response = $this->getJson("/api/funcionarios/{$funcionario->id}");
        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $funcionario->id);
    }

    public function test_pode_atualizar_funcionario()
    {
        $funcionario = Funcionario::factory()->create();
        $payload = ['nome' => 'Nome Atualizado'];
        $response = $this->putJson("/api/funcionarios/{$funcionario->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Nome Atualizado');

        $this->assertDatabaseHas('funcionarios', [
            'id' => $funcionario->id,
            'nome' => 'Nome Atualizado',
        ]);
    }

    public function test_pode_remover_funcionario()
    {
        $funcionario = Funcionario::factory()->create();
        $response = $this->deleteJson("/api/funcionarios/{$funcionario->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('funcionarios', ['id' => $funcionario->id]);
    }

    public function test_nao_pode_criar_funcionario_com_dados_invalidos()
    {
        $response = $this->postJson('/api/funcionarios', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'empresa_id',
                     'nome',
                 ]);
    }

    public function test_nao_pode_ver_funcionario_inexistente()
    {
        $response = $this->getJson('/api/funcionarios/999999');

        $response->assertStatus(404);
    }

    public function test_nao_pode_atualizar_funcionario_inexistente()
    {
        $response = $this->putJson('/api/funcionarios/999999', ['nome' => 'Nome Atualizado']);

        $response->assertStatus(404);
    }

    public function test_nao_pode_remover_funcionario_inexistente()
    {
        $response = $this->deleteJson('/api/funcionarios/999999');

        $response->assertStatus(404);
    }
}
