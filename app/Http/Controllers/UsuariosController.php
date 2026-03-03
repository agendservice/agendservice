<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                "id" => 1,
                "nome" => "João Silva",
                "email" => "joao@email.com",
                "telefone" => "85999990000",
                "tipo" => "cliente",
                "empresa" => null,
                "ativo" => true,
                "criadoEm" => "2026-02-20 14:00:00",
                "atualizadoEm" => "2026-02-20 14:00:00"
            ],
            [
                "id" => 2,
                "nome" => "Carlos Souza",
                "email" => "carlos@barbearia.com",
                "telefone" => "85988880000",
                "tipo" => "funcionario",
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
            "message" => "Usuário criado com sucesso",
            "data" => array_merge($request->all(), ["id" => 3])
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            "id" => (int)$id,
            "nome" => "Usuário Exemplo",
            "email" => "exemplo@email.com"
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            "message" => "Usuário atualizado com sucesso",
            "data" => array_merge($request->all(), ["id" => (int)$id])
        ]);
    }

    public function destroy($id)
    {
        return response()->json(["message" => "Usuário removido com sucesso"], 200);
    }
}
