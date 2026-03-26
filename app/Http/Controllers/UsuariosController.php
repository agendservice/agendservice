<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $usuario = Usuario::whereRaw('upper(email) = upper(?)', [$request->email])->first();
            Auth::login($usuario);

            return response()->json(['redirect' => '/dashboard']);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Falha na Autenticação.',
        ], 500);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['code' => 200], 200);
    }

    public function index()
    {
        return UsuarioResource::collection(Usuario::all());
    }

    public function buscar(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Usuario::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $usuarios = $query->paginate($perPage, ['*'], 'page', $page);

        return UsuarioResource::collection($usuarios);
    }

    public function menu()
    {
        $menu = [
            ['titulo' => 'Usuarios', 'link' => '/usuarios', 'icone' => 'mdi-file-document-outline'],
            ['titulo' => 'Empresas', 'link' => '/empresas', 'icone' => 'mdi-office-building-outline'],
            ['titulo' => 'Serviços', 'link' => '/servicos', 'icone' => 'mdi-scissors-cutting'],
            ['titulo' => 'Funcionários', 'link' => '/funcionarios', 'icone' => 'mdi-account-hard-hat-outline'],
            ['titulo' => 'Clientes', 'link' => '/clientes', 'icone' => 'mdi-account-group-outline'],
            ['titulo' => 'Agendamentos', 'link' => '/agendamentos', 'icone' => 'mdi-calendar-clock-outline'],
        ];

        return response()->json($menu);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nome' => 'required|string|max:255', 'email' => 'required|string|email|max:255|unique:usuarios', 'password' => 'required|string|min:8', 'telefone' => 'nullable|string|max:20', 'tipo' => 'string|in:admin,funcionario,cliente', 'status' => 'string|in:ativo,inativo']);
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        $usuario = Usuario::create($validated);

        return (new UsuarioResource($usuario))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        return new UsuarioResource(Usuario::findOrFail($id));
    }

    public function update(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
        $usuario = Usuario::findOrFail($id);
        $validated = $request->validate(['nome' => 'string|max:255', 'email' => 'string|email|max:255|unique:usuarios,email,'.$usuario->id, 'password' => 'string|min:8', 'telefone' => 'nullable|string|max:20', 'tipo' => 'string|in:admin,funcionario,cliente', 'status' => 'string|in:ativo,inativo']);
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
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
