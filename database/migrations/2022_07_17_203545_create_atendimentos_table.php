<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtendimentosTable extends Migration
{
    public function up()
    {
        Schema::create('tb_atendimentos', function (Blueprint $table) {
            $table->increments('id_atendimento')->unsigned();
            $table->date('date_emissao_atendimento');
            $table->dateTime('date_time_emissao_atendimento');

            $table->bigInteger('cpf')->unsigned()->nullable(); //bigIncrements->(8-byte)
            $table->enum('sufixo_atendimento', ['PDG', 'FCR', 'SCT', 'OTS']);
            $table->integer('numero_atendimento');
            $table->unsignedInteger('servicos')->nullable(); //array & FK

            $table->unsignedInteger('id_service_desk')->nullable(); //FK
            $table->string('observacoes')->nullable();

            //$table->dateTime('created')->nullable();
            $table->dateTime('first_call')->nullable();
            $table->dateTime('started')->nullable();
            $table->dateTime('finished')->nullable();
            $table->enum('status_atendimento', ['chamando', 'aguardando', 'em_atendimento', 'nao_compareceu', 'nao_concluido', 'concluido'])->nullable();
        });
        
        Schema::table('tb_atendimentos', function (Blueprint $table) {
            $table->foreign('id_service_desk')->references('id_service_desk')->on('tb_service_desks');
            $table->foreign('servicos')->references('id_servicos')->on('tb_servicos');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('tb_atendimentos');
    }
}
