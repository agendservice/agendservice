<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CadastroUsuarioTest extends TestCase
{
    // Este 'trait' mágico reseta o banco de dados a cada teste.
    // Garante que cada teste comece com um banco de dados limpo.
    use RefreshDatabase;
    use WithFaker;
    /**
     * Testa se um usuário pode ser cadastrado com sucesso com dados válidos.
     *
     * @return void
     */
    public function test_pode_cadastrar_um_novo_usuario_com_sucesso(): void
    {
        $this->withoutExceptionHandling();
        // 1. Organizar (Arrange): Preparamos os dados que seriam enviados pelo formulário.
        $dadosCadastro = [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'rg' => $this->faker->numerify('########'),
            'data_expedicao_rg' => $this->faker->date(),
            'password' => 'password123',
            'data_nascimento' => $this->faker->date(),
            'password_confirmation' => 'password123',
            'endereco' => $this->faker->address(),
            'cep' => $this->faker->postcode(),
            'temConjuge' => false,
            'nome_mae' => $this->faker->name(),
            'nome_pai' => $this->faker->name(),
            'profissao' => $this->faker->jobTitle(),
            'instagram' => $this->faker->userName(),
            'linkedin' => $this->faker->userName(),
            'facebook' => $this->faker->userName(),
        ];

        // 2. Agir (Act): Fazemos uma requisição POST para a rota do seu controller.
        $response = $this->postJson('/registro', $dadosCadastro);

        // 3. Verificar (Assert): Checamos se tudo ocorreu como esperado.
        $response->assertStatus(201); // Verifica se o status HTTP é "201 Created"
        $response->assertJson(['message' => 'Usuário cadastrado com sucesso!']);

        // A verificação mais importante: o usuário realmente existe no banco de dados?
        $this->assertDatabaseHas('usuarios', [
            'email' => $dadosCadastro['email'],
            'cpf' => $dadosCadastro['cpf']
        ]);
    }
    
    /**
     * Testa se o cadastro falha quando a confirmação de senha é inválida.
     *
     * @return void
     */
    public function test_falha_no_cadastro_com_senhas_diferentes(): void
    {
        $dadosCadastro = [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'rg' => $this->faker->numerify('########'),
            'password' => 'password123',
            'password_confirmation' => 'outra_senha_errada', // Senha incorreta
            'endereco' => $this->faker->address(),
            'cep' => $this->faker->postcode(),
            'temConjuge' => false,
        ];
        
        $response = $this->postJson('/registro', $dadosCadastro);
        
        // Verifica se a API retornou um erro de validação (422 Unprocessable Entity)
        $response->assertStatus(422);
        
        // Verifica se a mensagem de erro específica para a senha está presente
        $response->assertJsonValidationErrors(['password']);
        
        // Garante que nenhum usuário foi criado no banco de dados
        $this->assertDatabaseMissing('usuarios', [
            'email' => $dadosCadastro['email']
        ]);
    }

    /**
     * Testa o cadastro de um novo usuário que possui um cônjuge.
     *
     * @return void
     */
    public function test_pode_cadastrar_um_novo_usuario_com_conjuge(): void
    {
        $this->withoutExceptionHandling(); // Descomente para depurar erros 500

        // 1. Arrange: Preparamos os dados, incluindo os do cônjuge
        $dadosCadastro = [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'rg' => $this->faker->numerify('########'),
            'data_expedicao_rg' => $this->faker->date(),
            'password' => 'password123',
            'data_nascimento' => $this->faker->date(),
            'password_confirmation' => 'password123',
            'endereco' => $this->faker->address(),
            'cep' => $this->faker->postcode(),
            'temConjuge' => false,
            'nome_mae' => $this->faker->name(),
            'nome_pai' => $this->faker->name(),
            'profissao' => $this->faker->jobTitle(),
            'instagram' => $this->faker->userName(),
            'linkedin' => $this->faker->userName(),
            'facebook' => $this->faker->userName(),
            // --- Dados do Cônjuge ---
            'temConjuge' => true,
            'nome_conjuge' => $this->faker->name(),
            'cpf_conjuge' => $this->faker->unique()->numerify('###########'),
            'rg_conjuge' => $this->faker->numerify('########'),
            'data_expedicao_rg_conjuge' => $this->faker->date(),
            'data_nascimento_conjuge' => $this->faker->date(),
            'profissao_conjuge' => $this->faker->jobTitle(),
            'email_conjuge' => $this->faker->unique()->safeEmail(),
            'telefone_conjuge' => $this->faker->phoneNumber(),
        ];

        // 2. Act: Fazemos a requisição
        $response = $this->postJson('/registro', $dadosCadastro);

        // 3. Assert: Verificamos os resultados
        $response->assertStatus(201); // Garante que a requisição foi bem-sucedida

        // Verifica se o usuário principal foi criado corretamente
        $this->assertDatabaseHas('usuarios', [
            'email' => $dadosCadastro['email'],
            'cpf' => $dadosCadastro['cpf']
        ]);
        
        // A verificação mais importante: o cônjuge foi salvo no banco de dados?
        $this->assertDatabaseHas('conjuges', [
            'email' => $dadosCadastro['email_conjuge'],
            'cpf' => $dadosCadastro['cpf_conjuge']
        ]);

        // Verificação final: o cônjuge está corretamente associado ao usuário principal?
        // Buscamos o usuário que acabamos de criar
        $usuario = \App\Models\Usuario::where('email', $dadosCadastro['email'])->first();
        // Verificamos se o registro na tabela 'conjuges' tem o usuario_id correto
        $this->assertDatabaseHas('conjuges', [
            'cpf' => $dadosCadastro['cpf_conjuge'],
            'usuario_id' => $usuario->id
        ]);
    }
}