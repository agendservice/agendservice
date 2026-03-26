<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebEmpresasControllerTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): Usuario
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_rotas_web_de_empresa_exigem_autenticacao()
    {
        $this->post('/empresa', [])->assertStatus(302);
    }

    public function test_buscar_empresas_retorna_paginacao_e_aplica_filtros()
    {
        $this->autenticar();

        Empresa::factory()->create(['nome' => 'Empresa Alpha', 'email' => 'alpha@teste.com']);
        Empresa::factory()->create(['nome' => 'Empresa Beta', 'email' => 'beta@teste.com']);

        $response = $this->postJson('/empresa', [
            'nome' => 'Alpha',
            'per_page' => 1,
            'page' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'Empresa Alpha')
            ->assertJsonStructure([
                'data',
                'meta' => ['links'],
            ]);
    }

    public function test_pode_cadastrar_atualizar_e_remover_empresa_nas_rotas_web()
    {
        $this->autenticar();

        $create = $this->postJson('/empresa/cadastrar', [
            'nome' => 'Empresa Web',
            'cnpj' => '12.345.678/0001-90',
            'email' => 'empresa@web.com',
            'telefone' => '85999999999',
            'endereco' => 'Rua A',
        ]);

        $create->assertStatus(201);
        $id = $create->json('data.id');

        $this->putJson('/empresa/atualizar', [
            'id' => $id,
            'nome' => 'Empresa Web Atualizada',
        ])->assertStatus(200)
            ->assertJsonPath('data.nome', 'Empresa Web Atualizada');

        $this->assertDatabaseHas('empresas', [
            'id' => $id,
            'nome' => 'Empresa Web Atualizada',
        ]);

        $this->deleteJson('/empresa/' . $id)->assertStatus(204);
        $this->assertDatabaseMissing('empresas', ['id' => $id]);
    }
}
