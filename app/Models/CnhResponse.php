<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CnhResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'cnh_request_id',
        'categoria',
        'codigo_seguranca',
        'cpf',
        'emissao_data',
        'espelho',
        'mae',
        'nome',
        'nome_condutor_identico_ao_informado',
        'nome_mae_identico_ao_informado',
        'registro',
        'situacao',
        'validade_data',
    ];

    protected $casts = [
        'emissao_data' => 'date',
        'validade_data' => 'date',
        'nome_condutor_identico_ao_informado' => 'boolean',
        'nome_mae_identico_ao_informado' => 'boolean',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CnhRequest::class, 'cnh_request_id');
    }
}