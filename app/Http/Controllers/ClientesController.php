<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function historico($id)
    {
        $dados = [
            [
                "id" => 95,
                "servico" => [
                    "id" => 2,
                    "nome" => "Tratamento Capilar"
                ],
                "empresa" => [
                    "id" => 1,
                    "nome" => "Barbearia Estilo"
                ],
                "dataHoraInicio" => "2026-01-10 15:00:00",
                "status" => "concluido",
                "observacao" => "Primeira sessão de tratamento."
            ]
        ];

        return response()->json($dados);
    }
}
