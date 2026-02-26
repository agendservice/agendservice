<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Acesso;
use Illuminate\Support\Facades\Hash;

class EssentialUsersSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Verificando e criando usuários essenciais (Admin e Mentor)...');

        // Garante que os níveis de acesso existem antes de criar os usuários
        $acessoAdmin = Acesso::firstOrCreate(
            ['nome' => 'Administrador'],
            // Valores para preencher apenas se estiver criando um novo
            [
                'dashboard_admin' => 'S',
                'relatorios' => 'S',
                // Adicione outras permissões padrão para admin aqui
            ]
        );

        $acessoMentor = Acesso::firstOrCreate(
            ['nome' => 'Mentor'],
            [
                // Adicione permissões padrão para mentor aqui
                'horarios' => 'S',
                'agendamentos' => 'S',
            ]
        );

        // Cria o usuário Administrador se ele não existir
        Usuario::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'nome' => 'Administrador Principal',
                'password' => Hash::make('12345678'),
                'acesso_id' => $acessoAdmin->id,
                'status' => 'aprovado',
                'cpf' => '000.000.000-00', // CPF genérico para admin
            ]
        );

        // Cria o usuário Mentor se ele não existir
        Usuario::firstOrCreate(
            ['email' => 'mentor@email.com'],
            [
                'nome' => 'Mentor Padrão',
                'password' => Hash::make('12345678'),
                'acesso_id' => $acessoMentor->id,
                'status' => 'aprovado',
                'cpf' => '111.111.111-11', // CPF genérico para mentor
            ]
        );

        $this->command->info('Usuários essenciais verificados/criados com sucesso.');
    }
}