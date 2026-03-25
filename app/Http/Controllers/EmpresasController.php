<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresasController extends Controller
{
    public function index()
    {
        return EmpresaResource::collection(Empresa::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nome' => 'required|string|max:255', 'cnpj' => 'nullable|string|max:18', 'endereco' => 'nullable|string|max:255', 'telefone' => 'nullable|string|max:20']);
        $empresa = Empresa::create($validated);
        return (new EmpresaResource($empresa))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        return new EmpresaResource(Empresa::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        return new EmpresaResource($empresa);
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();
        return response()->noContent();
    }
}
