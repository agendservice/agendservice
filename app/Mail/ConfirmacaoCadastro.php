<?php

namespace App\Mail;

use App\Models\Usuario;
use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ConfirmacaoCadastro extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $usuario;
    public $empresa;
    public $link;

    /**
     * Cria uma nova instância de mensagem.
     *
     * @param Usuario $usuario
     * @param Empresa $empresa
     */
    public function __construct(Usuario $usuario, Empresa $empresa)
    {
        $this->usuario = $usuario;
        $this->empresa = $empresa;

        // Gera um link assinado temporário (válido por 48 horas)
        // Isso requer que você tenha uma rota nomeada 'verificar.email'
        $this->link = URL::temporarySignedRoute(
            'verificar.email',
            now()->addHours(48),
            ['id' => $usuario->id]
        );
    }

    /**
     * Constrói a mensagem.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmação de E-mail - ' . config('app.name'))
                    ->view('emails.confirmacao_email');
        // As variáveis públicas ($usuario, $empresa, $link)
        // são passadas automaticamente para a view.
    }
}
