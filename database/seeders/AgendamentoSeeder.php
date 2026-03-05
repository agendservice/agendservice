<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Servico;
use App\Models\Agendamento;

class AgendamentoSeeder extends Seeder
{
    public function run()
    {
        $cliente = Usuario::where('email', 'joao@email.com')->first();
        $funcionario = Usuario::where('email', 'carlos@barbearia.com')->first();
        $servico = Servico::first();

        if ($cliente && $funcionario && $servico) {
            Agendamento::create([
                'cliente_id' => $cliente->id,
                'servico_id' => $servico->id,
                'funcionario_id' => $funcionario->id,
                'dataHoraInicio' => '2026-03-10 14:00:00',
                'dataHoraFim' => '2026-03-10 14:40:00',
                'status' => 'confirmado',
                'observacao' => 'Corte mais baixo nas laterais.',
            ]);
        }
    }
}
