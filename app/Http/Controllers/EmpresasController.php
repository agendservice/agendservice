<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmpresasController extends Controller
{
    public function index()
    {
        return EmpresaResource::collection(Empresa::all());
    }

    public function buscar(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Empresa::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('cnpj')) {
            $query->where('cnpj', 'like', '%' . $request->cnpj . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $empresas = $query->paginate($perPage, ['*'], 'page', $page);

        return EmpresaResource::collection($empresas);
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

    public function update(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
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
