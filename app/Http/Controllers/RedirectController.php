<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    /**
     * Redireciona o usuário para o dashboard apropriado
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        
        if ($user->acesso_id == 2) {
            // Admin - redireciona para dashboard admin
            return redirect()->route('dashboard.admin');
        } else {
            // Parceiro - redireciona para dashboard padrão
            return redirect()->route('dashboard.default');
        }
    }
}
