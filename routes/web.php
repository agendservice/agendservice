<?php

use App\Http\Controllers\AgendamentosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\FuncionariosController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
// use App\Http\Controllers\FaturasController;
// Route::controller(FaturasController::class)->group(function () {
//     Route::middleware('auth')->post('/fatura/gerar', 'gerarFatura');
//     Route::middleware('auth')->get('/fatura/status/{referencia}', 'status');
// });

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }

    return view('login');
})->name('home');

Route::controller(UsuariosController::class)->group(function () {
    Route::post('/login', 'login');

    Route::middleware('auth')->group(function () {
        Route::post('/usuario', 'buscar');
        Route::get('/usuario/{id}', 'show');
        Route::post('/menu', 'menu');
        Route::post('/usuario/cadastrar', 'store');
        Route::put('/usuario/atualizar', 'update');
        Route::delete('/usuario/{id}', 'destroy');
        Route::post('/logout', 'logout');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(EmpresasController::class)->group(function () {
        Route::post('/empresa', 'buscar');
        Route::get('/empresa/{id}', 'show');
        Route::post('/empresa/cadastrar', 'store');
        Route::put('/empresa/atualizar', 'update');
        Route::delete('/empresa/{id}', 'destroy');
    });

    Route::controller(ServicosController::class)->group(function () {
        Route::post('/servico', 'buscar');
        Route::get('/servico/{id}', 'show');
        Route::post('/servico/cadastrar', 'store');
        Route::put('/servico/atualizar', 'update');
        Route::delete('/servico/{id}', 'destroy');
    });

    Route::controller(FuncionariosController::class)->group(function () {
        Route::post('/funcionario', 'buscar');
        Route::get('/funcionario/{id}', 'show');
        Route::post('/funcionario/cadastrar', 'store');
        Route::put('/funcionario/atualizar', 'update');
        Route::delete('/funcionario/{id}', 'destroy');
    });

    Route::controller(ClientesController::class)->group(function () {
        Route::post('/cliente', 'buscar');
        Route::get('/cliente/{id}', 'show');
        Route::post('/cliente/cadastrar', 'store');
        Route::put('/cliente/atualizar', 'update');
        Route::delete('/cliente/{id}', 'destroy');
    });

    Route::controller(AgendamentosController::class)->group(function () {
        Route::post('/agendamento', 'buscar');
        Route::get('/agendamento/{id}', 'show');
        Route::post('/agendamento/cadastrar', 'store');
        Route::put('/agendamento/atualizar', 'update');
        Route::delete('/agendamento/{id}', 'destroy');
    });
});

Route::get('/{any}', function ($any) {
    if (!Auth::check()) {
        return redirect('/');
    }

    return view('app');
})->where('any', '.*');
