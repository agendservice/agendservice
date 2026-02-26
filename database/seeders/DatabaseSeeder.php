<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // PRIMEIRO: Garante que os usuários essenciais existam.
            EssentialUsersSeeder::class,

            // SEGUNDO: Cria o cenário de teste com os parceiros.
            CenarioIndicacaoSeeder::class,

            // Adicione outros seeders aqui se necessário...
        ]);
    }
}