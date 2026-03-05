<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('telefone')->nullable()->after('email');
            $table->string('tipo')->default('cliente')->after('telefone');
            $table->unsignedBigInteger('empresa_id')->nullable()->after('tipo');
            $table->string('status')->default('ativo')->after('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['telefone', 'tipo', 'empresa_id', 'status']);
        });
    }
}
