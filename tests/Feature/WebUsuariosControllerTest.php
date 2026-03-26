<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebUsuariosControllerTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): Usuario
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_rotas_web_de_usuario_exigem_autenticacao()
    {
        $this->post('/usuario', [])->assertStatus(302);
    }

    public function test_buscar_usuarios_retorna_paginacao_e_aplica_filtros()
    {
        $this->autenticar();

        Usuario::factory()->create(['nome' => 'João Silva', 'email' => 'joao@teste.com', 'status' => 'ativo']);
        Usuario::factory()->create(['nome' => 'Maria Souza', 'email' => 'maria@teste.com', 'status' => 'inativo']);

        $response = $this->postJson('/usuario', [
            'nome' => 'João',
            'status' => 'ativo',
            'per_page' => 1,
            'page' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'João Silva')
            ->assertJsonStructure(['meta' => ['links']]);
    }

    public function test_menu_web_contem_novos_itens()
    {
        $this->autenticar();

        $response = $this->postJson('/menu');

        $response->assertStatus(200)->assertJsonFragment(['titulo' => 'Empresas']);
        $response->assertJsonFragment(['titulo' => 'Serviços']);
        $response->assertJsonFragment(['titulo' => 'Funcionários']);
        $response->assertJsonFragment(['titulo' => 'Clientes']);
        $response->assertJsonFragment(['titulo' => 'Agendamentos']);
    }

    public function test_pode_atualizar_usuario_com_id_no_body_na_rota_web()
    {
        $this->autenticar();
        $usuario = Usuario::factory()->create([
            'password' => bcrypt('senhaAntiga123'),
        ]);

        $this->putJson('/usuario/atualizar', [
            'id' => $usuario->id,
            'nome' => 'Usuário Atualizado',
            'password' => 'novaSenha123',
        ])->assertStatus(200)
            ->assertJsonPath('data.nome', 'Usuário Atualizado');

        $usuario->refresh();
        $this->assertSame('Usuário Atualizado', $usuario->nome);
        $this->assertTrue(Hash::check('novaSenha123', $usuario->password));
    }
}
