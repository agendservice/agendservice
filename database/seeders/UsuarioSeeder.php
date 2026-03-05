<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Criando usuarios de teste...');

        $usuario1 = new Usuario();
        $usuario1->nome = 'Usuario Teste';
        $usuario1->email = 'usuario1@example.com';
        $usuario1->password = bcrypt('12345678');
        $usuario1->telefone = '11999999999';
        $usuario1->status = 'ativo';
        $usuario1->save();

        $usuario2 = new Usuario();
        $usuario2->nome = 'Usuario Teste 2';
        $usuario2->email = 'usuario2@example.com';
        $usuario2->password = bcrypt('12345678');
        $usuario2->telefone = '11999999999';
        $usuario2->status = 'ativo';
        $usuario2->save();

        $usuario3 = new Usuario();
        $usuario3->nome = 'Usuario Teste 3';
        $usuario3->email = 'usuario3@example.com';
        $usuario3->password = bcrypt('12345678');
        $usuario3->telefone = '11999999999';
        $usuario3->status = 'ativo';
        $usuario3->save();

        $this->command->info('Usuarios de teste criados com sucesso.');

    }
}
