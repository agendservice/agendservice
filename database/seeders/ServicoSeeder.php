<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Servico;

class ServicoSeeder extends Seeder
{
    public function run()
    {
        $empresa = Empresa::first();

        if ($empresa) {
            Servico::create([
                'empresa_id' => $empresa->id,
                'nome' => 'Corte de Cabelo',
                'descricao' => 'Corte tradicional ou degradê.',
                'duracaoMinutos' => 40,
                'preco' => 35.00,
                'intervaloMinimoDias' => 0,
                'ativo' => true,
            ]);

            Servico::create([
                'empresa_id' => $empresa->id,
                'nome' => 'Tratamento Capilar',
                'descricao' => 'Hidratação profunda com produtos profissionais.',
                'duracaoMinutos' => 60,
                'preco' => 80.00,
                'intervaloMinimoDias' => 30,
                'ativo' => true,
            ]);
        }
    }
}
