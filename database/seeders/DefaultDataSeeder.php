<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Servico;
use App\Models\Funcionario;
use App\Models\Cliente;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('empresas')->truncate();
        DB::table('servicos')->truncate();
        DB::table('funcionarios')->truncate();
        DB::table('clientes')->truncate();
        DB::table('agendamentos')->truncate();
        DB::table('usuarios')->truncate();
        DB::table('personal_access_tokens')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Criar usuário 1 para login
        $usuario = Usuario::create([
            'nome' => 'Usuario Teste',
            'email' => 'usuario1@example.com',
            'password' => bcrypt('12345678'),
            'telefone' => '11999999999',
            'status' => 'ativo',
        ]);

        $empresa = Empresa::create([
            'nome' => 'Barbearia Estilo',
            'cnpj' => '12.345.678/0001-90',
            'endereco' => 'Rua das Flores, 123',
            'telefone' => '8533334444',
        ]);

        $servico1 = Servico::create([
            'empresa_id' => $empresa->id,
            'nome' => 'Corte de Cabelo',
            'descricao' => 'Corte tradicional ou degradê.',
            'duracao_minutos' => 40,
            'preco' => 35.00,
        ]);

        $funcionario = Funcionario::create([
            'empresa_id' => $empresa->id,
            'usuario_id' => $usuario->id,
            'nome' => 'Carlos Souza',
            'especialidade' => 'Barbeiro Sênior',
        ]);

        $cliente = Cliente::create([
            'usuario_id' => null,
            'nome' => 'João Silva',
            'email' => 'joao@cliente.com',
            'telefone' => '85988887777',
        ]);

        \App\Models\Agendamento::create([
            'cliente_id' => $cliente->id,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico1->id,
            'data_hora_inicio' => now()->addDay()->setHour(14)->setMinute(0),
            'data_hora_fim' => now()->addDay()->setHour(14)->setMinute(40),
            'status' => 'confirmado',
            'observacao' => 'Agendamento inicial de teste.',
        ]);
    }
}
