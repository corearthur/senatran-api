<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cnh_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cnh_request_id')->constrained()->onDelete('cascade');
            $table->string('categoria', 5);
            $table->string('codigo_seguranca');
            $table->string('cpf');
            $table->date('emissao_data');
            $table->string('espelho', 20);
            $table->string('mae');
            $table->string('nome');
            $table->boolean('nome_condutor_identico_ao_informado');
            $table->boolean('nome_mae_identico_ao_informado');
            $table->string('registro');
            $table->string('situacao', 50);
            $table->date('validade_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cnh_responses');
    }
};