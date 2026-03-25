<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\AgendamentosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FuncionariosController;


Route::apiResource('empresas', EmpresasController::class);
Route::apiResource('servicos', ServicosController::class);
Route::apiResource('agendamentos', AgendamentosController::class);
Route::apiResource('funcionarios', FuncionariosController::class);
Route::apiResource('clientes', ClientesController::class);
Route::apiResource('usuarios', UsuariosController::class)->names([
    'index' => 'usuarios.index'
]);
Route::get('/clientes/{id}/historico', [ClientesController::class, 'historico']);
Route::post('/login', [LoginController::class, 'login']);
