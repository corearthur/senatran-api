<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cnh_requests', function (Blueprint $table) {
            $table->id();
            $table->string('cpf')->index();
            $table->string('registro');
            $table->string('codigo_seguranca');
            $table->string('login_cpf')->nullable();
            $table->string('login_senha')->nullable();
            $table->string('nome_condutor')->nullable();
            $table->string('nome_mae')->nullable();
            $table->text('pkcs12_cert')->nullable();
            $table->string('pkcs12_pass')->nullable();
            $table->string('client_name')->nullable();
            $table->string('token_name')->nullable();
            $table->boolean('billable')->default(true);
            $table->decimal('price', 10, 2)->default(0.24);
            $table->string('remote_ip', 45)->nullable();
            $table->integer('elapsed_time_in_milliseconds')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cnh_requests');
    }
};