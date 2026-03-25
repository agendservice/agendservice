<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessosTable extends Migration
{
    public function up()
    {
        Schema::create('acessos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('dashboard_admin')->default('N');
            $table->string('dashboard_mentor')->default('N');
            $table->string('relatorios')->default('N');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acessos');
    }
}
