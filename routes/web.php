<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use App\Models\Usuario;
use App\Models\Empresa;

use App\Services\AcessoService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\FaturasController;
Route::controller(FaturasController::class)->group(function () {
    Route::middleware('auth')->post('/fatura/gerar', 'gerarFatura');
    Route::middleware('auth')->get('/fatura/status/{referencia}', 'status');
});

Route::get('/', function () {
    if (\Auth::check()) {
        // Redireciona usuário logado para o dashboard apropriado
        $user = \Auth::user();
        if ($user->acesso_id == 2) {
            // Admin - redireciona para dashboard admin
            return redirect('/dashboard/admin');
        } else {
            // Parceiro - redireciona para dashboard padrão
            return redirect('/dashboard/default');
        }
    } else {
        return view('login');
    }
})->name('home');

Route::get('/{any}', function ($any) {
    if (\Auth::check()) {
        $user = \Auth::user();
        
        // Lista de rotas que requerem acesso de admin
        $rotasAdmin = [
            'dashboard/admin', 'usuarios', 'vendas', 'contabilidade', 
            'metas', 'clientes', 'treinamentos', 'campanhas', 
            'cadastro', 'acessos', 'parametros', 'regras'
        ];
        
        $rotaLimpa = explode('/', $any)[0];
        if (count(explode('/', $any)) > 1) {
            $rotaLimpa = explode('/', $any)[0] . '/' . explode('/', $any)[1];
        }
        
        if ((in_array($rotaLimpa, $rotasAdmin) || in_array($any, $rotasAdmin)) && $user->acesso_id != 2) {
            return redirect('/dashboard/default')->with('error', 'Acesso negado para esta funcionalidade.');
        }
        
        return view('app');
    } else {
        $rotasPublicas = ['registro', 'registro-cliente', 'redefinir-senha'];
        foreach ($rotasPublicas as $rota) {
             if ($any === $rota || str_starts_with($any, $rota . '/')) {
                 return view('app');
             }
        }
        
        $slugEmpresa = explode('/', $any)[0];

        $empresa = Empresa::where('slug_empresa', $slugEmpresa)->first();
        if ($empresa) {
            return view('app');;
        }

        return redirect('/');
    }
})->where('any', '.*');
