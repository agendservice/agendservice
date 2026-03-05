<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'descricao',
        'duracaoMinutos',
        'preco',
        'intervaloMinimoDias',
        'ativo'
    ];

    protected $hidden = [
        'empresa_id',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['criadoEm', 'atualizadoEm'];

    protected $casts = [
        'ativo' => 'boolean',
        'preco' => 'float',
        'duracaoMinutos' => 'integer',
        'intervaloMinimoDias' => 'integer'
    ];

    protected $with = ['empresa'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class)->select(['id', 'nome']);
    }

    public function getCriadoEmAttribute(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getAtualizadoEmAttribute(): string
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
