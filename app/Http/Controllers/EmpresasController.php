<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresasController extends Controller
{
    public function buscar()
    {
        $empresas = [
            [
                "id" => 1,
                "nome" => "Barbearia Estilo" ,
                "descricao" => "Cortes modesrnos e atendimento premium.",
                "telefone" => "8533334444",
                "email" => "contato@estilo.com",
                "endereco" => "Rua das Flores, 123",
                "capacidadeSimultanea" => 3,
                "ativo" => true,
                "criadoEm" => "2026-02-15 09:00:00",
                "atualizadoEm" => "2026-02-15 09:00:00"
            ]
        ];

        return response()->json($empresas);
    }
}
