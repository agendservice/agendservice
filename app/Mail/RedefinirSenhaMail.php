<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RedefinirSenhaMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $codigo;
    public $nomeUsuario;

    /**
     * Crie uma nova instância da mensagem.
     *
     * @param string $codigo      o código de 6 dígitos
     * @param string $nomeUsuario o nome do usuário
     */
    public function __construct(string $codigo, string $nomeUsuario)
    {
        $this->codigo = $codigo;
        $this->nomeUsuario = $nomeUsuario;
    }

    /**
     * Constrói a mensagem de e-mail.
     *
     * @return $this
     */
    public function build()
    {
        // Este método 'build()' substitui os métodos 'envelope()' e 'content()'.
        // Ele usará automaticamente o remetente (From) definido no seu .env.

        return $this
            ->subject('Seu Código de Redefinição de Senha') // Define o assunto
            ->view('emails.redefinir_senha')              // Define o template Blade
            ->with([                                     // Passa as variáveis para o template
                'codigo' => $this->codigo,
                'nome' => $this->nomeUsuario,
            ]);
    }
}
