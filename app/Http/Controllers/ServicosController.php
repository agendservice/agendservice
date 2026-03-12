<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicosController extends Controller
{
    public function index()
    {
        return response()->json([
            [
                'id' => 1,
                'nome' => 'Corte de Cabelo',
                'descricao' => 'Corte tradicional ou degradê.',
                'duracaoMinutos' => 40,
                'preco' => 35.00,
                'intervaloMinimoDias' => 0,
                'empresa' => [
                    'id' => 1,
                    'nome' => 'Barbearia Estilo',
                ],
                'ativo' => true,
                'criadoEm' => '2026-02-15 09:30:00',
                'atualizadoEm' => '2026-02-15 09:30:00',
            ],
            [
                'id' => 2,
                'nome' => 'Tratamento Capilar',
                'descricao' => 'Hidratação profunda com produtos profissionais.',
                'duracaoMinutos' => 60,
                'preco' => 80.00,
                'intervaloMinimoDias' => 30,
                'empresa' => [
                    'id' => 1,
                    'nome' => 'Barbearia Estilo',
                ],
                'ativo' => true,
                'criadoEm' => '2026-02-15 10:00:00',
                'atualizadoEm' => '2026-02-15 10:00:00',
            ],
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Serviço criado com sucesso',
            'data' => array_merge($request->all(), ['id' => 3]),
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'id' => (int) $id,
            'nome' => 'Serviço Exemplo',
            'preco' => 50.00,
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'message' => 'Serviço atualizado com sucesso',
            'data' => array_merge($request->all(), ['id' => (int) $id]),
        ]);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Serviço removido com sucesso'], 200);
    }
}
