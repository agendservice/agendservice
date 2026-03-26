<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientesController extends Controller
{
    public function index()
    {
        return ClienteResource::collection(Cliente::with('usuario')->get());
    }

    public function buscar(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Cliente::with('usuario');

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        $clientes = $query->paginate($perPage, ['*'], 'page', $page);

        return ClienteResource::collection($clientes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'nullable|exists:usuarios,id',
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'nullable|string|max:20',
        ]);
        $cliente = Cliente::create($validated);

        return (new ClienteResource($cliente))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        return new ClienteResource(Cliente::with('usuario')->findOrFail($id));
    }

    public function update(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return new ClienteResource($cliente);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->noContent();
    }

    public function historico($id)
    {
        $cliente = Cliente::findOrFail($id);
        $agendamentos = \App\Models\Agendamento::where('cliente_id', $id)->with(['servico', 'funcionario.empresa'])->get();

        return \App\Http\Resources\AgendamentoResource::collection($agendamentos);
    }
}
