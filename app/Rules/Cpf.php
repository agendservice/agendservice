<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cpf implements Rule
{
    public function passes($attribute, $value)
    {
        // Limpeza
        $cpf = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Validação Matemática
        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $multiplicador = (($t + 1) - $c);
                $digitoAtual = (int) $cpf[$c];
                $soma += $digitoAtual * $multiplicador;
            }

            $resto = ($soma * 10) % 11;
            $digitoCalculado = ($resto == 10 || $resto == 11) ? 0 : $resto;

            $digitoReal = (int) $cpf[$t];

            if ($digitoReal !== $digitoCalculado) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'O CPF informado não é válido.';
    }
}
