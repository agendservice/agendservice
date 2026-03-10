<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FuncionariosController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                "id" => 2,
                "nome" => "Carlos Souza",
                "email" => "carlos@barbearia.com",
                "empresa" => [
                    "id" => 1,
                    "nome" => "Barbearia Estilo"
                ],
                "ativo" => true,
                "criadoEm" => "2026-02-18 10:00:00",
                "atualizadoEm" => "2026-02-18 10:00:00"
            ]
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            "message" => "Funcionário cadastrado com sucesso",
            "data" => array_merge($request->all(), ["id" => 3])
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            "id" => (int) $id,
            "nome" => "Funcionário Exemplo",
            "email" => "exemplo@barbearia.com"
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            "message" => "Funcionário atualizado com sucesso",
            "data" => array_merge($request->all(), ["id" => (int) $id])
        ]);
    }

    public function destroy($id)
    {
        return response()->json(["message" => "Funcionário removido com sucesso"], 200);
    }
}
