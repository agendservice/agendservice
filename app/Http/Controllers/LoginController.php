<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dados = [
            "token" => "jwt.token.aqui",
            "usuario" => [
                "id" => 1,
                "nome" => "João Silva",
                "tipo" => "cliente"
            ]
        ];

        return response()->json($dados);
    }
}
