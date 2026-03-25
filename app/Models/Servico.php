<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'empresa_id',
        'nome',
        'descricao',
        'duracao_minutos',
        'preco',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
