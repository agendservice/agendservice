<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Validation\ValidationException;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            // Validação dos dados de entrada
            $data = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:usuarios',
                'password' => 'required|string|min:8',
                'telefone' => 'required|string|max:20',
                'status' => 'string|in:ativo,inativo',
            ], [
                'nome.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.email' => 'O campo email deve ser um endereço de email válido.',
                'email.unique' => 'O email em uso.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve conter pelo menos 8 caracteres.',
                'telefone.required' => 'O campo telefone é obrigatório.',
                'status.in' => 'O campo status deve ser "ativo" ou "inativo".',
            ]);

            $usuario = new Usuario();
            $usuario->nome = $data['nome'];
            $usuario->email = $data['email'];
            $usuario->password = bcrypt($data['password']);
            $usuario->telefone = $data['telefone'];
            $usuario->status = $data['status'];
            $usuario->save();

            \DB::commit();

            return response()->json([
                "message" => "Usuário criado com sucesso",
                "data" => $usuario
            ], 201);

        } catch (ValidationException $e) {
            \DB::rollback();
            \Log::warning('Validação falhou ao criar usuário: ' . json_encode($e->errors()));
            return response()->json([
                "message" => "Dados inválidos",
                "errors" => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Erro ao criar usuário: ' . $e->getMessage());
            return response()->json(["message" => "Erro ao criar usuário"], 500);
        }

    }

    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(["message" => "Usuário não encontrado"], 404);
        }
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::find($id);
            if (!$usuario) {
                return response()->json(["message" => "Usuário não encontrado"], 404);
            }

            // Validação dos dados de entrada
            $data = $request->validate([
                'nome' => 'string|max:255',
                'email' => 'string|email|max:255|unique:usuarios,email,' . $usuario->id,
                'password' => 'string|min:8',
                'telefone' => 'string|max:20',
                'status' => 'string|in:ativo,inativo',
            ], [
                'email.unique' => 'O email já está em uso.',
                'password.min' => 'A senha deve conter pelo menos 8 caracteres.',
                'status.in' => 'O campo status deve ser "ativo" ou "inativo".',
            ]);

            // Atualiza os campos do usuário
            $usuario->fill($data);
            $usuario->save();

            return response()->json([
                "message" => "Usuário atualizado com sucesso",
                "data" => $usuario
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => "Dados inválidos",
                "errors" => $e->errors()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(["message" => "Usuário não encontrado"], 404);
        }

        $usuario->delete();
        return response()->json(["message" => "Usuário removido com sucesso"], 200);
    }
}
