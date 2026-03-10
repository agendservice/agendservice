<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$allowedAccessLevels)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // Se não foram especificados níveis de acesso, permite para qualquer usuário autenticado
        if (empty($allowedAccessLevels)) {
            return $next($request);
        }

        // Verifica se o usuário tem um dos níveis de acesso permitidos
        if (in_array($user->acesso_id, $allowedAccessLevels)) {
            return $next($request);
        }

        // Se não tem permissão, redireciona para o dashboard apropriado
        if ($user->acesso_id == 2) {
            return redirect('/dashboard/admin')->with('error', 'Acesso negado para esta funcionalidade.');
        } else {
            return redirect('/dashboard/default')->with('error', 'Acesso negado para esta funcionalidade.');
        }
    }
}
