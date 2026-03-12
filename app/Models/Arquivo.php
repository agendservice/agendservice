<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Arquivo extends Model
{
    use HasFactory;

    protected $table = 'arquivos';

    protected $appends = ['public_url'];

    protected $fillable = [
        'user_id',
        'tipo_documento',
        'nome_original',
        'path',
        'mime_type',
        'size',
        'status',
        'rejeicao_motivo',
        'indicacao_id',
    ];

    public function getPublicUrlAttribute(): string
    {
        if ($this->path && Storage::disk('public')->exists($this->path)) {
            return Storage::disk('public')->url($this->path);
        }

        return '';
    }
}
