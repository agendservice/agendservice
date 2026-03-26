<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Servico;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebServicosControllerTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): Usuario
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_rotas_web_de_servico_exigem_autenticacao()
    {
        $this->post('/servico', [])->assertStatus(302);
    }

    public function test_buscar_servicos_retorna_paginacao_e_aplica_filtros()
    {
        $this->autenticar();

        $empresaA = Empresa::factory()->create();
        $empresaB = Empresa::factory()->create();

        Servico::factory()->create(['empresa_id' => $empresaA->id, 'nome' => 'Corte Premium']);
        Servico::factory()->create(['empresa_id' => $empresaB->id, 'nome' => 'Barba']);

        $response = $this->postJson('/servico', [
            'empresa_id' => $empresaA->id,
            'nome' => 'Corte',
            'per_page' => 1,
            'page' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'Corte Premium')
            ->assertJsonPath('data.0.empresa_id', $empresaA->id)
            ->assertJsonStructure(['meta' => ['links']]);
    }

    public function test_pode_cadastrar_atualizar_e_remover_servico_nas_rotas_web()
    {
        $this->autenticar();

        $empresa = Empresa::factory()->create();

        $create = $this->postJson('/servico/cadastrar', [
            'empresa_id' => $empresa->id,
            'nome' => 'Serviço Web',
            'descricao' => 'Descrição',
            'duracao_minutos' => 40,
            'preco' => 75.50,
        ]);

        $create->assertStatus(201);
        $id = $create->json('data.id');

        $this->putJson('/servico/atualizar', [
            'id' => $id,
            'nome' => 'Serviço Web Atualizado',
        ])->assertStatus(200)
            ->assertJsonPath('data.nome', 'Serviço Web Atualizado');

        $this->assertDatabaseHas('servicos', [
            'id' => $id,
            'nome' => 'Serviço Web Atualizado',
        ]);

        $this->deleteJson('/servico/' . $id)->assertStatus(204);
        $this->assertDatabaseMissing('servicos', ['id' => $id]);
    }
}
