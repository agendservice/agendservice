<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Http\Resources\UsuarioResource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificacaoCadastro;
use App\Mail\ConfirmacaoCadastro;
use Illuminate\Support\Facades\Auth;
use App\Rules\Cpf;
use App\Rules\Cnpj;
use App\Rules\Telefone;

class UsuarioController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $usuario = Usuario::whereRaw("upper(email) = upper(?)", [$request->email])->first();
            Auth::login($usuario);
            $user = Auth::user();
            return response()->json(['redirect' => '/dashboard']);
        }

        return response()->json([
            'code'      =>  500,
            'message'   =>  'Falha na Autenticação.'
        ], 500);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'code'      =>  200
        ], 200);
    }


    public function menu()
    {
        $menu = [
            ['titulo' => 'Crud blank', 'link' => '/crud-blank', 'icone' => 'mdi-file-document-outline'],
        ];
        return response()->json($menu);
    }

    public function index(Request $request)
    {
        $usuarios = Usuario::query();
        if ($request->has('filtros')) {
            $filtros = $request->input('filtros');
            if (isset($filtros['nome'])) {
                $usuarios->where('nome', 'like', '%' . $filtros['nome'] . '%');
            }
            if (isset($filtros['email'])) {
                $usuarios->where('email', 'like', '%' . $filtros['email'] . '%');
            }
        }
        $usuarios = $usuarios->paginate($request->input('per_page', 10));
        return response()->json(UsuarioResource::collection($usuarios)->response()->getData(true));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(new UsuarioResource($usuario), 201);
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json(new UsuarioResource($usuario));
    }

    public function update(Request $request)
    {
        $usuario = Usuario::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($usuario->id)],
            'password' => 'nullable|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        if ($request->password) {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->save();

        return response()->json(new UsuarioResource($usuario));
    }

}
