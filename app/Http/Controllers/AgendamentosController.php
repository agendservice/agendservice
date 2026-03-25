<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AgendamentoResource;
use App\Models\Agendamento;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendamentosController extends Controller
{
    public function index()
    {
        return AgendamentoResource::collection(Agendamento::with(['cliente', 'funcionario', 'servico'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'funcionario_id' => 'required|exists:funcionarios,id',
            'servico_id' => 'required|exists:servicos,id',
            'data_hora_inicio' => 'required|date|after:now',
            'observacao' => 'nullable|string',
        ]);

        $servico = Servico::findOrFail($validated['servico_id']);
        $inicio = Carbon::parse($validated['data_hora_inicio']);
        $fim = $inicio->copy()->addMinutes($servico->duracao_minutos);

        $sobreposicao = Agendamento::where('funcionario_id', $validated['funcionario_id'])
            ->where(function ($query) use ($inicio, $fim) {
                $query->whereBetween('data_hora_inicio', [$inicio, $fim->copy()->subSecond()])
                    ->orWhereBetween('data_hora_fim', [$inicio->copy()->addSecond(), $fim]);
            })->exists();

        if ($sobreposicao) {
            return response()->json(['message' => 'O funcionário já possui um agendamento neste horário.'], 422);
        }

        $agendamento = Agendamento::create(array_merge($validated, ['data_hora_fim' => $fim, 'status' => 'pendente']));

        return (new AgendamentoResource($agendamento->load(['cliente', 'funcionario', 'servico'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        return new AgendamentoResource(Agendamento::with(['cliente', 'funcionario', 'servico'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update($request->all());
        return new AgendamentoResource($agendamento->load(['cliente', 'funcionario', 'servico']));
    }

    public function destroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();
        return response()->noContent();
    }
}
