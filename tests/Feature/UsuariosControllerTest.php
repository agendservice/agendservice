<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Usuario;

class UsuariosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_usuarios_com_tipos_corretos()
    {
        Usuario::factory()->create();

        $response = $this->getJson('/api/usuarios');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data')
                    ->has('data.0', fn (AssertableJson $json) =>
                        $json->whereAllType([
                            'id' => 'integer',
                            'nome' => 'string',
                            'email' => 'string'
                        ])->etc()
                    )
            );
    }

    public function test_pode_criar_usuario()
    {
        $payload = [
            'nome' => 'Novo Usuário', 
            'email' => 'usuarioteste@email.com', 
            'password' => 'senha1234'
        ];
        $response = $this->postJson('/api/usuarios', $payload);
        
        $response->assertStatus(201)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('data', fn (AssertableJson $json) =>
                        $json->where('nome', 'Novo Usuário')
                             ->etc()
                    )
                 );
    }

    public function test_pode_ver_usuario()
    {
        $usuario = Usuario::factory()->create();
        $response = $this->getJson("/api/usuarios/{$usuario->id}");
        $response->assertStatus(200)
                 ->assertJson(fn (AssertableJson $json) =>
                    $json->has('data', fn (AssertableJson $json) =>
                        $json->where('id', $usuario->id)
                             ->etc()
                    )
                 );
    }

    public function test_pode_atualizar_usuario()
    {
        $usuario = Usuario::factory()->create();
        $payload = ['nome' => 'Nome Atualizado'];
        $response = $this->putJson("/api/usuarios/{$usuario->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonPath('data.nome', 'Nome Atualizado');
    }

    public function test_pode_remover_usuario()
    {
        $usuario = Usuario::factory()->create();
        $response = $this->deleteJson("/api/usuarios/{$usuario->id}");
        $response->assertStatus(204);
    }
}
