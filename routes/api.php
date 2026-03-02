<?php

use App\Http\Controllers\EmpresasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(EmpresasController::class)->group(function () {
    Route::get('/empresas', 'buscar');
});