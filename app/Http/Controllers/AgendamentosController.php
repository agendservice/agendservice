<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendamentosController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                "id" => 101,
                "cliente" => [
                    "id" => 1,
                    "nome" => "João Silva"
                ],
                "servico" => [
                    "id" => 1,
                    "nome" => "Corte de Cabelo",
                    "duracaoMinutos" => 40,
                    "preco" => 35.00,
                    "empresa" => [
                        "id" => 1,
                        "nome" => "Barbearia Estilo"
                    ]
                ],
                "dataHoraInicio" => "2026-03-10 14:00:00",
                "dataHoraFim" => "2026-03-10 14:40:00",
                "status" => "confirmado",
                "observacao" => "Corte mais baixo nas laterais.",
                "criadoEm" => "2026-02-26 11:00:00",
                "atualizadoEm" => "2026-02-26 11:00:00"
            ]
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            "message" => "Agendamento realizado com sucesso",
            "data" => array_merge($request->all(), ["id" => 102])
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            "id" => (int) $id,
            "status" => "confirmado",
            "dataHoraInicio" => "2026-03-10 15:00:00"
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            "message" => "Agendamento atualizado com sucesso",
            "data" => array_merge($request->all(), ["id" => (int) $id])
        ]);
    }

    public function destroy($id)
    {
        return response()->json(["message" => "Agendamento cancelado com sucesso"], 200);
    }
}
