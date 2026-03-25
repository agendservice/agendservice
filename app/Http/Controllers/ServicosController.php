<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ServicoResource;
use App\Models\Servico;
use Illuminate\Http\Request;

class ServicosController extends Controller
{
    public function index()
    {
        return ServicoResource::collection(Servico::with('empresa')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'duracao_minutos' => 'required|integer|min:1',
            'preco' => 'required|numeric|min:0',
        ]);
        $servico = Servico::create($validated);

        return (new ServicoResource($servico))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        return new ServicoResource(Servico::with('empresa')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->update($request->all());

        return new ServicoResource($servico);
    }

    public function destroy($id)
    {
        $servico = Servico::findOrFail($id);
        $servico->delete();

        return response()->noContent();
    }
}
