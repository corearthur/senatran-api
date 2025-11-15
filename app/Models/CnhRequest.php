<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CnhRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpf',
        'registro',
        'codigo_seguranca',
        'login_cpf',
        'login_senha',
        'nome_condutor',
        'nome_mae',
        'client_name',
        'token_name',
        'billable',
        'price',
        'remote_ip',
        'elapsed_time_in_milliseconds',
    ];

    protected $casts = [
        'billable' => 'boolean',
        'price' => 'decimal:2',
        'elapsed_time_in_milliseconds' => 'integer',
    ];

    public function response(): HasOne
    {
        return $this->hasOne(CnhResponse::class);
    }
}