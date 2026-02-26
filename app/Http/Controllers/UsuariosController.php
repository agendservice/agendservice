<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
  public function buscar()
  {
    $dados = [
      [
        "id" => 1,
        "nome" => "João Silva",
        "email" => "joao@email.com",
        "telefone" => "85999990000",
        "tipo" => "cliente",
        "empresa" => null,
        "ativo" => true,
        "criadoEm" => "2026-02-20 14:00:00",
        "atualizadoEm" => "2026-02-20 14:00:00"
      ],
      [
        "id" => 2,
        "nome" => "Carlos Souza",
        "email" => "carlos@barbearia.com",
        "telefone" => "85988880000",
        "tipo" => "funcionario",
        "empresa" => [
          "id" => 1,
          "nome" => "Barbearia Estilo"
        ],
        "ativo" => true,
        "criadoEm" => "2026-02-18 10:00:00",
        "atualizadoEm" => "2026-02-18 10:00:00"
      ]
    ];

    return response()->json($dados);
  }
  
}