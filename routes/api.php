<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// No routes

use App\Http\Controllers\UsuariosController;

Route::controller(UsuariosController::class)->group(function () {
    Route::get('/usuarios', 'buscar');
});
