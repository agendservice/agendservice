<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Rejeicao;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sobrenome',
        'cpf',
        'telefone',
        'img_perfil',
        'ultimo_login',
        'acesso',
        'status',
        'email',
        'password',
        'remember_token',
        'empresa_id'
    ];

    // Definir status 
    const STATUS_APROVADA = 'aprovada';
    const STATUS_PENDENTE = 'pendente';
    const STATUS_REJEITADA = 'rejeitada';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    public function acesso() 
    {
        return $this->belongsTo('App\Models\Acesso', 'acesso_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        $data = date_create($value);
        return $data->format('d/m/Y H:i:s');
    }
 
    public function getUpdatedAtAttribute($value)
    {
        $data = date_create($value);
        return $data->format('d/m/Y H:i:s');
    }

    public function imagem() {
        return $this->belongsTo('App\Models\Imagem', 'imagem_id', 'id');
    }

    public function rejeicoes()
    {
        return $this->hasMany(Rejeicao::class, 'user_id');
    }

    public function metas() {
        return $this->hasMany(Meta::class, 'user_id');
    }

    public function informes() {
        return $this->hasMany(Informes::class);
    }

    /**
     * Relacionamento: usuários que este usuário indicou
     */
    public function indicados() {
        return $this->hasMany(Indicacao::class, 'indicador_id');
    }
    
    /**
     * Relacionamento: a indicação MAIS RECENTE onde este usuário foi indicado.
     */
    public function indicacaoMaisRecente()
    {
        return $this->hasOne(Indicacao::class, 'indicado_id')->latestOfMany();
    }

    /**
     * Define a relação onde este usuário é o INDICADO em uma ou mais indicações.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indicacoesComoIndicado()
    {
        // Um usuário pode ter muitas (hasMany) indicações onde ele é o 'indicado_id'.
        return $this->hasMany(Indicacao::class, 'indicado_id');
    }

    /**
     * Relacionamento: usuário que indicou este usuário
     */
    public function indicador() {
        return $this->hasOneThrough(Usuario::class, Indicacao::class, 'indicado_id', 'id', 'id', 'indicador_id');
    }
    /*
    * Relacionamento: um Usuário tem um Cônjuge.
    */
    public function conjuge(): HasOne
    {
        return $this->hasOne(Conjuge::class);
    }

    public function agendamentosComoMentorado()
    {
        return $this->hasMany(Agendamento::class, 'mentorado_id');
    }

    public function agendamentosComoMentor()
    {
        return $this->hasMany(Agendamento::class, 'mentor_id');
    }

    public function contatos()
    {
        return $this->hasMany(MentorContato::class, 'usuario_id');
    }

    public function preferencia()
    {
        return $this->hasOne(PreferenciaUsuario::class);
    }
}
