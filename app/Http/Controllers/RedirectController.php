<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    /**
     * Redireciona o usuário para o dashboard apropriado.
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if (2 == $user->acesso_id) {
            // Admin - redireciona para dashboard admin
            return redirect()->route('dashboard.admin');
        }

        // Parceiro - redireciona para dashboard padrão
        return redirect()->route('dashboard.default');
    }
}
