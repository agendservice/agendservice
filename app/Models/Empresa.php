<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'nome',
        'cnpj',
        'endereco',
        'telefone',
    ];

    public function servicos()
    {
        return $this->hasMany(Servico::class);
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
