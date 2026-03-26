<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AgendamentoResource;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Services\AgendamentoIntervalosRepository;
use App\Services\AgendamentoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AgendamentosController extends Controller
{
    public function __construct(
        private readonly AgendamentoIntervalosRepository $agendamentoIntervalosRepository,
        private readonly AgendamentoService $agendamentoService,
    ) {
    }

    public function index()
    {
        return AgendamentoResource::collection(Agendamento::with(['cliente', 'funcionario', 'servico'])->get());
    }

    public function buscar(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Agendamento::with(['cliente', 'funcionario', 'servico']);

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('funcionario_id')) {
            $query->where('funcionario_id', $request->funcionario_id);
        }

        if ($request->filled('servico_id')) {
            $query->where('servico_id', $request->servico_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_hora_inicio')) {
            $query->whereDate('data_hora_inicio', Carbon::parse($request->data_hora_inicio));
        }

        $agendamentos = $query->orderBy('data_hora_inicio', 'desc')->paginate($perPage, ['*'], 'page', $page);

        return AgendamentoResource::collection($agendamentos);
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
        $fim = Carbon::instance($this->agendamentoService->calcularFim($inicio, (int) $servico->duracao_minutos));

        $intervalos = $this->agendamentoIntervalosRepository->listarDoFuncionarioNoDia((int) $validated['funcionario_id'], $inicio);
        $sobreposicao = $this->agendamentoService->existeSobreposicao($inicio, $fim, $intervalos);

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

    public function update(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
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
