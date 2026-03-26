<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebFuncionariosControllerTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): Usuario
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_rotas_web_de_funcionario_exigem_autenticacao()
    {
        $this->post('/funcionario', [])->assertStatus(302);
    }

    public function test_buscar_funcionarios_retorna_paginacao_e_aplica_filtros()
    {
        $this->autenticar();

        $empresaA = Empresa::factory()->create();
        $empresaB = Empresa::factory()->create();

        Funcionario::factory()->create(['empresa_id' => $empresaA->id, 'nome' => 'Carlos']);
        Funcionario::factory()->create(['empresa_id' => $empresaB->id, 'nome' => 'Joana']);

        $response = $this->postJson('/funcionario', [
            'empresa_id' => $empresaA->id,
            'nome' => 'Carl',
            'per_page' => 1,
            'page' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'Carlos')
            ->assertJsonPath('data.0.empresa_id', $empresaA->id)
            ->assertJsonStructure(['meta' => ['links']]);
    }

    public function test_pode_cadastrar_atualizar_e_remover_funcionario_nas_rotas_web()
    {
        $this->autenticar();

        $empresa = Empresa::factory()->create();
        $usuario = Usuario::factory()->create();

        $create = $this->postJson('/funcionario/cadastrar', [
            'empresa_id' => $empresa->id,
            'usuario_id' => $usuario->id,
            'nome' => 'Funcionário Web',
            'especialidade' => 'Colorista',
        ]);

        $create->assertStatus(201);
        $id = $create->json('data.id');

        $this->putJson('/funcionario/atualizar', [
            'id' => $id,
            'nome' => 'Funcionário Atualizado',
        ])->assertStatus(200)
            ->assertJsonPath('data.nome', 'Funcionário Atualizado');

        $this->assertDatabaseHas('funcionarios', [
            'id' => $id,
            'nome' => 'Funcionário Atualizado',
        ]);

        $this->deleteJson('/funcionario/' . $id)->assertStatus(204);
        $this->assertDatabaseMissing('funcionarios', ['id' => $id]);
    }
}
