<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebClientesControllerTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): Usuario
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_rotas_web_de_cliente_exigem_autenticacao()
    {
        $this->post('/cliente', [])->assertStatus(302);
    }

    public function test_buscar_clientes_retorna_paginacao_e_aplica_filtros()
    {
        $this->autenticar();

        $usuario = Usuario::factory()->create();
        Cliente::factory()->create(['usuario_id' => $usuario->id, 'nome' => 'Cliente Alpha', 'email' => 'alpha@cliente.com']);
        Cliente::factory()->create(['nome' => 'Cliente Beta', 'email' => 'beta@cliente.com']);

        $response = $this->postJson('/cliente', [
            'usuario_id' => $usuario->id,
            'nome' => 'Alpha',
            'per_page' => 1,
            'page' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'Cliente Alpha')
            ->assertJsonPath('data.0.usuario_id', $usuario->id)
            ->assertJsonStructure(['meta' => ['links']]);
    }

    public function test_pode_cadastrar_atualizar_e_remover_cliente_nas_rotas_web()
    {
        $this->autenticar();

        $usuario = Usuario::factory()->create();

        $create = $this->postJson('/cliente/cadastrar', [
            'usuario_id' => $usuario->id,
            'nome' => 'Cliente Web',
            'email' => 'cliente.web@teste.com',
            'telefone' => '85999999999',
        ]);

        $create->assertStatus(201);
        $id = $create->json('data.id');

        $this->putJson('/cliente/atualizar', [
            'id' => $id,
            'nome' => 'Cliente Atualizado',
        ])->assertStatus(200)
            ->assertJsonPath('data.nome', 'Cliente Atualizado');

        $this->assertDatabaseHas('clientes', [
            'id' => $id,
            'nome' => 'Cliente Atualizado',
        ]);

        $this->deleteJson('/cliente/' . $id)->assertStatus(204);
        $this->assertDatabaseMissing('clientes', ['id' => $id]);
    }
}
