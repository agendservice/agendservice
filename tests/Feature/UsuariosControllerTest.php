<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

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

        $this->assertDatabaseHas('usuarios', [
            'nome' => 'Novo Usuário',
            'email' => 'usuarioteste@email.com',
        ]);
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

        $this->assertDatabaseHas('usuarios', [
            'id' => $usuario->id,
            'nome' => 'Nome Atualizado',
        ]);
    }

    public function test_pode_atualizar_senha_do_usuario()
    {
        $usuario = Usuario::factory()->create();

        $payload = ['password' => 'novaSenha1234'];
        $response = $this->putJson("/api/usuarios/{$usuario->id}", $payload);

        $response->assertStatus(200);

        $usuarioAtualizado = Usuario::findOrFail($usuario->id);
        $this->assertTrue(Hash::check('novaSenha1234', $usuarioAtualizado->password));
    }

    public function test_pode_remover_usuario()
    {
        $usuario = Usuario::factory()->create();
        $response = $this->deleteJson("/api/usuarios/{$usuario->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('usuarios', ['id' => $usuario->id]);
    }

    public function test_nao_pode_criar_usuario_sem_senha()
    {
        $payload = [
            'nome' => 'Novo Usuário',
            'email' => 'usuarioteste@email.com',
        ];

        $response = $this->postJson('/api/usuarios', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_nao_pode_criar_usuario_com_email_duplicado()
    {
        Usuario::factory()->create(['email' => 'duplicado@email.com']);

        $payload = [
            'nome' => 'Novo Usuário',
            'email' => 'duplicado@email.com',
            'password' => 'senha1234',
        ];

        $response = $this->postJson('/api/usuarios', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_nao_pode_atualizar_usuario_com_email_duplicado()
    {
        $usuario1 = Usuario::factory()->create(['email' => 'usuario1@email.com']);
        $usuario2 = Usuario::factory()->create(['email' => 'usuario2@email.com']);

        $response = $this->putJson("/api/usuarios/{$usuario2->id}", ['email' => $usuario1->email]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_nao_pode_ver_usuario_inexistente()
    {
        $response = $this->getJson('/api/usuarios/999999');

        $response->assertStatus(404);
    }

    public function test_nao_pode_atualizar_usuario_inexistente()
    {
        $response = $this->putJson('/api/usuarios/999999', ['nome' => 'Nome Atualizado']);

        $response->assertStatus(404);
    }

    public function test_nao_pode_remover_usuario_inexistente()
    {
        $response = $this->deleteJson('/api/usuarios/999999');

        $response->assertStatus(404);
    }

    public function test_senha_criptografada_no_momento_da_criacao()
    {
        $plainPassword = 'senhaSegura123';
        $payload = [
            'nome' => 'Usuario Hashed',
            'email' => 'hashed@email.com',
            'password' => $plainPassword,
        ];

        $this->postJson('/api/usuarios', $payload)
             ->assertStatus(201);

        $usuario = Usuario::where('email', 'hashed@email.com')->first();

        $this->assertTrue(Hash::check($plainPassword, $usuario->password));
    }

    public function test_senha_criptografada_no_momento_da_atualizacao()
    {
        $usuario = Usuario::factory()->create([
            'password' => bcrypt('senhaAntiga123'),
        ]);

        $plainPassword = 'senhaNova123';

        $this->putJson("/api/usuarios/{$usuario->id}", [
            'password' => $plainPassword,
        ])->assertStatus(200);

        $usuario->refresh();

        $this->assertTrue(Hash::check($plainPassword, $usuario->password));
    }
}
