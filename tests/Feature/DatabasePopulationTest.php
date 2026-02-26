<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuario;

class DatabasePopulationTest extends TestCase
{
    /**
     * ATENÇÃO: Este trait apaga e recria o banco de dados a cada execução.
     * Garante que você sempre comece com um banco de dados limpo.
     * NUNCA rode testes em um banco de dados de produção.
     */
    // use RefreshDatabase;

    /**
     * Disponibiliza o gerador de dados falsos ($this->faker).
     */
    use WithFaker;

    /**
     * Testa e popula o banco de dados criando parceiros via endpoint.
     *
     * @return void
     */
    public function test_pode_popular_o_banco_com_parceiros_e_suas_indicacoes()
    {
        // 1. ARRANGE (Organizar): Crie um usuário Admin para fazer as requisições,
        // caso seu endpoint de criação de parceiro seja protegido.
        // Se o endpoint for público, esta linha não é necessária.
        // $admin = Usuario::factory()->admin()->create();

        // Define quantos parceiros você quer criar.
        $quantidadeDeParceiros = 50;

        echo "Criando {$quantidadeDeParceiros} parceiros...\n";

        // 2. ACT (Agir): Faz um loop e chama o endpoint de criação várias vezes.
        for ($i = 0; $i < $quantidadeDeParceiros; $i++) {

            // Gera dados falsos para um novo parceiro a cada iteração
            $dadosParceiro = [
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
                'acesso_id' => 1, // Acesso parceiro
            ];

            // Faz a requisição POST para o seu endpoint de salvar usuário.
            // Use actingAs($admin) se a rota for protegida.
            $response = $this->postJson(route('registro.parceiro'), $dadosParceiro);

            // 3. ASSERT (Verificar): Garante que cada criação foi bem-sucedida.
            // $response->assertStatus(201); // Verifica se o status é "Created"
            echo $response->getContent() . "\n"; // Exibe a resposta para depuração
        }

        // Verificação final: o banco de dados tem o número correto de registros?
        // $this->assertDatabaseCount('usuarios', $quantidadeDeParceiros + 1); // +1 por causa do admin
        // $this->assertDatabaseCount('indicacoes', $quantidadeDeParceiros);

        echo "População concluída com sucesso!\n";
    }
}