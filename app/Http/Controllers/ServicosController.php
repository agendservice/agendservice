<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicosController extends Controller
{
    public function index(Request $request)
    {
        $query = Servico::with('empresa');

        if ($request->has('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $servico = Servico::create($request->all());
        return response()->json([
            "message" => "Serviço criado com sucesso",
            "data" => $servico->load('empresa')
        ], 201);
    }

    public function show($id)
    {
        $servico = Servico::with('empresa')->findOrFail($id);
        return response()->json($servico);
    }

    public function update(Request $request, $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->update($request->all());
        return response()->json([
            "message" => "Serviço atualizado com sucesso",
            "data" => $servico->load('empresa')
        ]);
    }

    public function destroy($id)
    {
        $servico = Servico::findOrFail($id);
        $servico->delete();
        return response()->json(["message" => "Serviço removido com sucesso"], 200);
    }
}
