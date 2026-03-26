<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\FuncionarioResource;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FuncionariosController extends Controller
{
    public function index()
    {
        return FuncionarioResource::collection(Funcionario::with(['empresa', 'usuario'])->get());
    }

    public function buscar(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Funcionario::with(['empresa', 'usuario']);

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        $funcionarios = $query->paginate($perPage, ['*'], 'page', $page);

        return FuncionarioResource::collection($funcionarios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'usuario_id' => 'nullable|exists:usuarios,id',
            'nome' => 'required|string|max:255',
            'especialidade' => 'nullable|string|max:255',
        ]);
        $funcionario = Funcionario::create($validated);

        return (new FuncionarioResource($funcionario))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        return new FuncionarioResource(Funcionario::with(['empresa', 'usuario'])->findOrFail($id));
    }

    public function update(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->update($request->all());

        return new FuncionarioResource($funcionario);
    }

    public function destroy($id)
    {
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->delete();

        return response()->noContent();
    }
}
