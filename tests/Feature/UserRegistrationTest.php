<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuario;

class UserRegistrationTest extends TestCase
{
    // A trait que garante um banco de dados limpo para cada teste. Essencial!
    use RefreshDatabase;
    // A trait que nos dá acesso ao gerador de dados falsos, o Faker.
    use WithFaker;

    /**
     * Testa o cenário feliz: um usuário se cadastra com sucesso sem cônjuge.
     * @test
     */
    public function um_usuario_pode_se_cadastrar_com_sucesso_sem_conjuge(): void
    {
        // 1. Arrange (Preparar os dados)
        // Usamos o Faker para gerar dados realistas e diferentes a cada teste.
        $userData = [
            'nome' => $this->faker->name(),
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'rg' => $this->faker->unique()->numerify('#########'),
            'data_expedicao_rg' => $this->faker->date(),
            'data_nascimento' => $this->faker->date(),
            'nome_mae' => $this->faker->name('female'),
            'telefone' => $this->faker->numerify('(##) #####-####'),
            'profissao' => $this->faker->jobTitle(),
            'endereco' => $this->faker->address(),
            'cep' => $this->faker->numerify('#####-###'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'temConjuge' => false, // Explicitamente indicar que não tem cônjuge
        ];

        // 2. Act (Executar a ação)
        // Simulamos uma requisição POST JSON para a sua rota '/registro'.
        $response = $this->postJson('/registro', $userData);

        // 3. Assert (Verificar o resultado)
        // A resposta foi bem-sucedida e retornou a mensagem correta?
        $response->assertStatus(201)
                 ->assertJson(['message' => 'Usuário cadastrado com sucesso!']);

        // O usuário foi realmente salvo no banco de dados?
        $this->assertDatabaseHas('usuarios', [
            'email' => $userData['email'],
            'nome' => $userData['nome'],
        ]);
    }

    /**
     * Testa o cenário feliz: um usuário se cadastra com sucesso COM cônjuge.
     * @test
     */
    public function um_usuario_pode_se_cadastrar_com_sucesso_com_conjuge(): void
    {
        // Desativa o tratamento de exceções para este teste.
        // Se houver um erro 500 no seu controller, veremos a causa exata.
        $this->withoutExceptionHandling();

        // 1. Arrange
        $userData = [
            'nome' => $this->faker->name(),
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'rg' => $this->faker->unique()->numerify('#########'),
            'data_expedicao_rg' => $this->faker->date(),
            'data_nascimento' => $this->faker->date(),
            'nome_mae' => $this->faker->name('female'),
            'telefone' => $this->faker->numerify('(##) #####-####'),
            'profissao' => $this->faker->jobTitle(),
            'endereco' => $this->faker->address(),
            'cep' => $this->faker->numerify('#####-###'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // --- Dados do Cônjuge ---
            'temConjuge' => true, // Chave essencial para ativar a lógica
            'nome_conjuge' => $this->faker->name(),
            'cpf_conjuge' => $this->faker->unique()->numerify('###.###.###-##'),
            'rg_conjuge' => $this->faker->unique()->numerify('#########'),
            'data_expedicao_rg_conjuge' => $this->faker->date(),
            'data_nascimento_conjuge' => $this->faker->date(),
            'telefone_conjuge' => $this->faker->numerify('(##) #####-####'),
            'profissao_conjuge' => $this->faker->jobTitle(),
            'email_conjuge' => $this->faker->unique()->safeEmail(),
        ];

        // 2. Act
        $response = $this->postJson('/registro', $userData);

        // 3. Assert
        $response->assertStatus(201);

        // Verifica se o usuário principal foi criado
        $this->assertDatabaseHas('usuarios', [
            'email' => $userData['email'],
            'cpf' => $userData['cpf'],
        ]);

        // Busca o usuário que acabamos de criar para verificar a associação
        $usuario = Usuario::where('email', $userData['email'])->first();

        // Verifica se os dados do cônjuge foram salvos e associados ao usuário correto
        $this->assertDatabaseHas('conjuges', [
            'nome' => $userData['nome_conjuge'],
            'cpf' => $userData['cpf_conjuge'],
            'usuario_id' => $usuario->id,
        ]);
    }

    /**
     * Testa o cenário triste: o cadastro falha com dados inválidos.
     * @test
     */
    public function o_cadastro_falha_se_os_dados_forem_invalidos(): void
    {
        // 1. Arrange
        $userData = [
            'nome' => '', // Nome em branco (deve falhar na validação 'required')
            'email' => 'email-invalido', // Email com formato inválido
            'password' => '123',
            'password_confirmation' => '456', // Senhas não coincidem
        ];

        // 2. Act
        // Usamos postJson para facilitar a verificação de erros de validação em JSON.
        $response = $this->postJson('/registro', $userData);

        // 3. Assert
        // 422 é o status HTTP para falha de validação.
        $response->assertStatus(422);

        // Verifica se a resposta JSON contém erros para os campos específicos.
        $response->assertJsonValidationErrors(['nome', 'email', 'password']);

        // Garante que NENHUM usuário foi criado no banco com esses dados.
        $this->assertDatabaseMissing('usuarios', [
            'email' => 'email-invalido',
        ]);
    }
}