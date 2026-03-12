<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Telefone implements Rule
{
    /**
     * Determina se a regra de validação é aprovada.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $telefone = preg_replace('/[^0-9]/is', '', $value);

        if (\strlen($telefone) < 10 || \strlen($telefone) > 11) {
            return false;
        }
        if (preg_match('/(\d)\1{9,10}/', $telefone)) {
            return false;
        }

        return true;
    }

    /**
     * Obtém a mensagem de erro da validação.
     *
     * @return string
     */
    public function message()
    {
        return 'O telefone informado não é válido.';
    }
}
