<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\FuncionarioResource;
use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionariosController extends Controller
{
    public function index()
    {
        return FuncionarioResource::collection(Funcionario::with(['empresa', 'usuario'])->get());
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

    public function update(Request $request, $id)
    {
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
