<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendamentosController extends Controller
{
    public function index(Request $request)
    {
        $query = Agendamento::with(['cliente', 'servico.empresa', 'funcionario']);

        if ($request->has('data')) {
            $query->whereDate('dataHoraInicio', $request->data);
        }

        if ($request->has('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:usuarios,id',
            'servico_id' => 'required|exists:servicos,id',
            'funcionario_id' => 'nullable|exists:usuarios,id',
            'dataHoraInicio' => 'required|date',
            'observacao' => 'nullable|string'
        ]);

        $servico = Servico::findOrFail($data['servico_id']);
        
        $inicio = Carbon::parse($data['dataHoraInicio']);
        $fim = $inicio->copy()->addMinutes($servico->duracaoMinutos);
        
        $data['dataHoraFim'] = $fim;

        $agendamento = Agendamento::create($data);

        return response()->json([
            "message" => "Agendamento realizado com sucesso",
            "data" => $agendamento->load(['cliente', 'servico', 'funcionario'])
        ], 201);
    }

    public function show($id)
    {
        $agendamento = Agendamento::with(['cliente', 'servico', 'funcionario'])->findOrFail($id);
        return response()->json($agendamento);
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        
        $data = $request->all();
        
        if (isset($data['dataHoraInicio']) || isset($data['servico_id'])) {
            $servicoId = $data['servico_id'] ?? $agendamento->servico_id;
            $servico = Servico::findOrFail($servicoId);
            
            $inicio = Carbon::parse($data['dataHoraInicio'] ?? $agendamento->dataHoraInicio);
            $data['dataHoraFim'] = $inicio->copy()->addMinutes($servico->duracaoMinutos);
        }

        $agendamento->update($data);

        return response()->json([
            "message" => "Agendamento atualizado com sucesso",
            "data" => $agendamento->load(['cliente', 'servico', 'funcionario'])
        ]);
    }

    public function destroy($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();
        return response()->json(["message" => "Agendamento cancelado com sucesso"], 200);
    }
}
