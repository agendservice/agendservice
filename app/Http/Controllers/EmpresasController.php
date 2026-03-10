<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresasController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                "id" => 1,
                "nome" => "Barbearia Estilo",
                "descricao" => "Cortes modernos e atendimento premium.",
                "telefone" => "8533334444",
                "email" => "contato@estilo.com",
                "endereco" => "Rua das Flores, 123",
                "capacidadeSimultanea" => 3,
                "ativo" => true,
                "criadoEm" => "2026-02-15 09:00:00",
                "atualizadoEm" => "2026-02-15 09:00:00"
            ]
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            "message" => "Empresa criada com sucesso",
            "data" => array_merge($request->all(), ["id" => 2])
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            "id" => (int) $id,
            "nome" => "Empresa Exemplo",
            "email" => "exemplo@empresa.com"
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            "message" => "Empresa atualizada com sucesso",
            "data" => array_merge($request->all(), ["id" => (int) $id])
        ]);
    }

    public function destroy($id)
    {
        return response()->json(["message" => "Empresa removida com sucesso"], 200);
    }
}
