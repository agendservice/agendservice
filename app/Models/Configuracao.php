<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    // Nome alterado de configuracaos para configuracoes
    protected $table = 'configuracoes';

    protected $fillable = ['chave', 'valor', 'descricao'];
}