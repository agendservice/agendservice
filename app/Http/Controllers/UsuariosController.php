<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index()
    {
        return UsuarioResource::collection(Usuario::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nome' => 'required|string|max:255', 'email' => 'required|string|email|max:255|unique:usuarios', 'password' => 'required|string|min:8', 'telefone' => 'nullable|string|max:20', 'tipo' => 'string|in:admin,funcionario,cliente', 'status' => 'string|in:ativo,inativo']);
        if (isset($validated['password'])) { $validated['password'] = bcrypt($validated['password']); }
        $usuario = Usuario::create($validated);
        return (new UsuarioResource($usuario))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        return new UsuarioResource(Usuario::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $validated = $request->validate(['nome' => 'string|max:255', 'email' => 'string|email|max:255|unique:usuarios,email,'.$usuario->id, 'password' => 'string|min:8', 'telefone' => 'nullable|string|max:20', 'tipo' => 'string|in:admin,funcionario,cliente', 'status' => 'string|in:ativo,inativo']);
        if (isset($validated['password'])) { $validated['password'] = bcrypt($validated['password']); }
        $usuario->update($validated);
        return new UsuarioResource($usuario);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return response()->noContent();
    }
}
