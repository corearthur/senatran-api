<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API SENATRAN - Validação de CNH",
 *     version="2.0.0",
 *     description="API para validação e cadastro de CNH (Carteira Nacional de Habilitação)",
 *     @OA\Contact(
 *         email="contato@exemplo.com",
 *         name="Suporte API"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor da API"
 * )
 * 
 * @OA\Tag(
 *     name="CNH",
 *     description="Operações relacionadas a CNH"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}