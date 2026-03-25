<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id',
        'nome',
        'email',
        'telefone',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
