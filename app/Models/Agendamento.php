<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'servico_id',
        'funcionario_id',
        'dataHoraInicio',
        'dataHoraFim',
        'status',
        'observacao'
    ];

    protected $hidden = [
        'cliente_id',
        'servico_id',
        'funcionario_id',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['empresa', 'criadoEm', 'atualizadoEm'];

    protected $with = ['cliente', 'servico', 'funcionario'];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id')->select(['id', 'nome']);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Usuario::class, 'funcionario_id')->select(['id', 'nome']);
    }

    /**
     * Accessor para empresa no nível do agendamento
     */
    public function getEmpresaAttribute()
    {
        return $this->servico ? $this->servico->empresa : null;
    }

    public function getCriadoEmAttribute(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function getAtualizadoEmAttribute(): string
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }
}
