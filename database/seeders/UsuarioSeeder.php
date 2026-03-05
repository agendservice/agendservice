<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        // Usuário Administrador
        Usuario::create([
            'nome' => 'Admin Sistema',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('123456'),
        ]);

        // Usuário Funcionário
        Usuario::create([
            'nome' => 'Carlos Souza',
            'email' => 'carlos@barbearia.com',
            'password' => Hash::make('123456'),
        ]);

        // Usuário Cliente
        Usuario::create([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
