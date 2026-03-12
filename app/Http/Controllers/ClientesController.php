<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ClientesController extends Controller
{
    public function historico($id)
    {
        $dados = [
            [
                'id' => 95,
                'servico' => [
                    'id' => 2,
                    'nome' => 'Tratamento Capilar',
                ],
                'empresa' => [
                    'id' => 1,
                    'nome' => 'Barbearia Estilo',
                ],
                'dataHoraInicio' => '2026-01-10 15:00:00',
                'status' => 'concluido',
                'observacao' => 'Primeira sessão de tratamento.',
            ],
        ];

        return response()->json($dados);
    }
}
