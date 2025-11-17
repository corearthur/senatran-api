<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CnhRequest;
use App\Models\CnhResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CnhController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v2/senatran/buscar-cnh",
     *     summary="Buscar CNH",
     *     description="Lista todas as CNHs cadastradas no sistema ou busca por CPF/registro específico.",
     *     operationId="buscarCnh",
     *     tags={"CNH"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         required=true,
     *         description="Token de autenticação da API",
     *         @OA\Schema(type="string", example="seu_token_aqui")
     *     ),
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         required=false,
     *         description="CPF do condutor (opcional - para busca específica)",
     *         @OA\Schema(type="string", example="123.456.789-01")
     *     ),
     *     @OA\Parameter(
     *         name="registro",
     *         in="query",
     *         required=false,
     *         description="Número de registro da CNH (opcional - para busca específica)",
     *         @OA\Schema(type="string", example="11111111111")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CNHs encontradas com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="code_message", type="string", example="CNHs encontradas com sucesso."),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string")),
     *             @OA\Property(
     *                 property="header",
     *                 type="object",
     *                 @OA\Property(property="api_version", type="string", example="v2"),
     *                 @OA\Property(property="service", type="string", example="senatran/buscar-cnh"),
     *                 @OA\Property(property="billable", type="boolean", example=false),
     *                 @OA\Property(property="price", type="string", example="0.00"),
     *                 @OA\Property(property="elapsed_time_in_milliseconds", type="integer", example=50)
     *             ),
     *             @OA\Property(property="data_count", type="integer", example=6),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="categoria", type="string", example="AB"),
     *                     @OA\Property(property="codigo_seguranca", type="string", example="12345678901"),
     *                     @OA\Property(property="cpf", type="string", example="123.456.789-01"),
     *                     @OA\Property(property="emissao_data", type="string", example="06/02/2018"),
     *                     @OA\Property(property="espelho", type="string", example="9876543210"),
     *                     @OA\Property(property="mae", type="string", example="Maria da Silva"),
     *                     @OA\Property(property="nome", type="string", example="João da Silva"),
     *                     @OA\Property(property="registro", type="string", example="11111111111"),
     *                     @OA\Property(property="situacao", type="string", example="válida"),
     *                     @OA\Property(property="validade_data", type="string", example="05/02/2028")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhuma CNH encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="code_message", type="string", example="Nenhuma CNH encontrada."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="Nenhuma CNH foi encontrada no sistema.")
     *             ),
     *             @OA\Property(property="data_count", type="integer", example=0),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="code_message", type="string", example="Dados de entrada inválidos."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="Token é obrigatório.")
     *             )
     *         )
     *     )
     * )
     */
    public function buscarCnh(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'token' => 'nullable|string',
            'cpf' => 'nullable|string',
            'registro' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'code_message' => 'Dados de entrada inválidos.',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        // Buscar CNH no banco de dados
        $query = CnhResponse::with('request');
        
        // Se informou CPF ou registro, busca específica
        if ($request->filled('cpf')) {
            $query->whereHas('request', function($q) use ($request) {
                $q->where('cpf', $request->cpf);
            });
        }
        
        if ($request->filled('registro')) {
            $query->whereHas('request', function($q) use ($request) {
                $q->where('registro', $request->registro);
            });
        }

        $cnhs = $query->get();

        // Se não encontrou nenhuma CNH
        if ($cnhs->isEmpty()) {
            $mensagem = ($request->filled('cpf') || $request->filled('registro')) 
                ? 'Nenhuma CNH foi encontrada com os dados informados.'
                : 'Nenhuma CNH foi encontrada no sistema.';
            
            return response()->json([
                'code' => 404,
                'code_message' => 'Nenhuma CNH encontrada.',
                'errors' => [$mensagem],
                'header' => [
                    'api_version' => 'v2',
                    'service' => 'senatran/buscar-cnh',
                    'parameters' => [
                        'cpf' => $request->cpf,
                        'registro' => $request->registro,
                    ],
                    'requested_at' => now()->format('Y-m-d\TH:i:s.vP'),
                    'remote_ip' => $request->ip(),
                ],
                'data_count' => 0,
                'data' => [],
                'site_receipts' => []
            ], 404);
        }

        // Calcular tempo de execução
        $elapsedTime = (int)((microtime(true) - $startTime) * 1000);

        // Mapear dados das CNHs
        $data = $cnhs->map(function($cnh) {
            return [
                'id' => $cnh->id,
                'categoria' => $cnh->categoria,
                'codigo_seguranca' => $cnh->codigo_seguranca,
                'cpf' => $cnh->cpf,
                'emissao_data' => $cnh->emissao_data->format('d/m/Y'),
                'espelho' => $cnh->espelho,
                'mae' => $cnh->mae,
                'nome' => $cnh->nome,
                'registro' => $cnh->registro,
                'situacao' => $cnh->situacao,
                'validade_data' => $cnh->validade_data->format('d/m/Y'),
            ];
        });

        return response()->json([
            'code' => 200,
            'code_message' => 'CNHs encontradas com sucesso.',
            'errors' => [],
            'header' => [
                'api_version' => 'v2',
                'service' => 'senatran/buscar-cnh',
                'parameters' => [
                    'cpf' => $request->cpf,
                    'registro' => $request->registro,
                ],
                'billable' => false,
                'price' => '0.00',
                'requested_at' => now()->format('Y-m-d\TH:i:s.vP'),
                'elapsed_time_in_milliseconds' => $elapsedTime,
                'remote_ip' => $request->ip(),
                'token_name' => $request->token,
            ],
            'data_count' => $cnhs->count(),
            'data' => $data,
            'site_receipts' => []
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/v2/senatran/salvar-cnh",
     *     summary="Salvar CNH",
     *     description="Cadastra uma nova CNH no sistema. Valida se CPF ou registro já existem antes de cadastrar.",
     *     operationId="salvarCnh",
     *     tags={"CNH"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da CNH a ser cadastrada",
     *         @OA\JsonContent(
     *             required={"token","cpf","registro","codigo_seguranca","nome_condutor","nome_mae","categoria","emissao_data","validade_data","espelho","situacao"},
     *             @OA\Property(property="token", type="string", example="seu_token_aqui", description="Token de autenticação da API"),
     *             @OA\Property(property="cpf", type="string", example="777.888.999-00", description="CPF do condutor"),
     *             @OA\Property(property="registro", type="string", example="77777777777", description="Número de registro da CNH"),
     *             @OA\Property(property="codigo_seguranca", type="string", example="77788899900", description="Código de segurança da CNH"),
     *             @OA\Property(property="nome_condutor", type="string", example="Roberto Carlos Santos", description="Nome completo do condutor"),
     *             @OA\Property(property="nome_mae", type="string", example="Helena Santos", description="Nome completo da mãe"),
     *             @OA\Property(property="categoria", type="string", example="AB", description="Categoria da CNH (A, B, AB, C, D, E, AC, AD, AE)"),
     *             @OA\Property(property="emissao_data", type="string", example="15/03/2020", description="Data de emissão da CNH (formato: dd/mm/yyyy)"),
     *             @OA\Property(property="validade_data", type="string", example="15/03/2030", description="Data de validade da CNH (formato: dd/mm/yyyy)"),
     *             @OA\Property(property="espelho", type="string", example="4444444444", description="Número do espelho da CNH"),
     *             @OA\Property(property="situacao", type="string", enum={"válida","suspensa","cassada","vencida"}, example="válida", description="Situação atual da CNH"),
     *             @OA\Property(property="login_cpf", type="string", example="777.888.999-00", description="CPF para login GOV.BR (opcional)"),
     *             @OA\Property(property="login_senha", type="string", example="senha123", description="Senha para login GOV.BR (opcional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="CNH cadastrada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="code_message", type="string", example="CNH cadastrada com sucesso."),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string")),
     *             @OA\Property(
     *                 property="header",
     *                 type="object",
     *                 @OA\Property(property="api_version", type="string", example="v2"),
     *                 @OA\Property(property="service", type="string", example="senatran/salvar-cnh"),
     *                 @OA\Property(property="billable", type="boolean", example=false),
     *                 @OA\Property(property="price", type="string", example="0.00"),
     *                 @OA\Property(property="elapsed_time_in_milliseconds", type="integer", example=150)
     *             ),
     *             @OA\Property(property="data_count", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="categoria", type="string", example="AB"),
     *                     @OA\Property(property="cpf", type="string", example="777.888.999-00"),
     *                     @OA\Property(property="registro", type="string", example="77777777777"),
     *                     @OA\Property(property="codigo_seguranca", type="string", example="77788899900"),
     *                     @OA\Property(property="nome", type="string", example="Roberto Carlos Santos"),
     *                     @OA\Property(property="mae", type="string", example="Helena Santos"),
     *                     @OA\Property(property="emissao_data", type="string", example="15/03/2020"),
     *                     @OA\Property(property="validade_data", type="string", example="15/03/2030"),
     *                     @OA\Property(property="espelho", type="string", example="4444444444"),
     *                     @OA\Property(property="situacao", type="string", example="válida"),
     *                     @OA\Property(property="nome_condutor_identico_ao_informado", type="boolean", example=true),
     *                     @OA\Property(property="nome_mae_identico_ao_informado", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="CNH já cadastrada - CPF ou registro duplicado",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=409),
     *             @OA\Property(property="code_message", type="string", example="CNH já cadastrada no sistema."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="Já existe uma CNH cadastrada com este CPF.")
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cpf", type="string", example="777.888.999-00"),
     *                 @OA\Property(property="registro", type="string", example="77777777777")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="code_message", type="string", example="Dados de entrada inválidos."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="The cpf field is required.")
     *             )
     *         )
     *     )
     * )
     */
    public function salvarCnh(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'cpf' => 'required|string',
            'registro' => 'required|string',
            'codigo_seguranca' => 'required|string',
            'nome_condutor' => 'required|string',
            'nome_mae' => 'required|string',
            'categoria' => 'required|string',
            'emissao_data' => 'required|date_format:d/m/Y',
            'validade_data' => 'required|date_format:d/m/Y',
            'espelho' => 'required|string',
            'situacao' => 'required|string|in:válida,suspensa,cassada,vencida',
            'login_cpf' => 'nullable|string',
            'login_senha' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'code_message' => 'Dados de entrada inválidos.',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        // Verificar se já existe uma CNH com esse CPF e registro
        $cnhComMesmoCpf = CnhResponse::whereHas('request', function($query) use ($request) {
            $query->where('cpf', $request->cpf);
        })->first();

        $cnhComMesmoRegistro = CnhResponse::whereHas('request', function($query) use ($request) {
            $query->where('registro', $request->registro);
        })->first();

        $erros = [];
        if ($cnhComMesmoCpf) {
            $erros[] = 'Já existe uma CNH cadastrada com este CPF.';
        }
        if ($cnhComMesmoRegistro) {
            $erros[] = 'Já existe uma CNH cadastrada com este número de registro.';
        }

        if (!empty($erros)) {
            return response()->json([
                'code' => 409,
                'code_message' => 'CNH já cadastrada no sistema.',
                'errors' => $erros,
                'data' => [
                    'cpf' => $request->cpf,
                    'registro' => $request->registro,
                ]
            ], 409);
        }

        // Criar requisição
        $cnhRequest = CnhRequest::create([
            'cpf' => $request->cpf,
            'registro' => $request->registro,
            'codigo_seguranca' => $request->codigo_seguranca,
            'login_cpf' => $request->login_cpf ?? null,
            'login_senha' => $request->login_senha ? bcrypt($request->login_senha) : null,
            'nome_condutor' => $request->nome_condutor,
            'nome_mae' => $request->nome_mae,
            'client_name' => 'Sistema Cadastro',
            'token_name' => $request->token,
            'billable' => false,
            'price' => 0.00,
            'remote_ip' => $request->ip(),
        ]);

        // Criar resposta (CNH)
        $cnhResponse = CnhResponse::create([
            'cnh_request_id' => $cnhRequest->id,
            'categoria' => $request->categoria,
            'codigo_seguranca' => $request->codigo_seguranca,
            'cpf' => $request->cpf,
            'emissao_data' => Carbon::createFromFormat('d/m/Y', $request->emissao_data),
            'espelho' => $request->espelho,
            'mae' => $request->nome_mae,
            'nome' => $request->nome_condutor,
            'nome_condutor_identico_ao_informado' => true,
            'nome_mae_identico_ao_informado' => true,
            'registro' => $request->registro,
            'situacao' => $request->situacao,
            'validade_data' => Carbon::createFromFormat('d/m/Y', $request->validade_data),
        ]);

        // Calcular tempo de execução
        $elapsedTime = (int)((microtime(true) - $startTime) * 1000);
        $cnhRequest->update(['elapsed_time_in_milliseconds' => $elapsedTime]);

        return response()->json([
            'code' => 201,
            'code_message' => 'CNH cadastrada com sucesso.',
            'errors' => [],
            'header' => [
                'api_version' => 'v2',
                'service' => 'senatran/salvar-cnh',
                'parameters' => [
                    'cpf' => $request->cpf,
                    'registro' => $request->registro,
                    'codigo_seguranca' => $request->codigo_seguranca,
                    'nome_condutor' => $request->nome_condutor,
                    'nome_mae' => $request->nome_mae,
                ],
                'client_name' => $cnhRequest->client_name,
                'token_name' => $cnhRequest->token_name,
                'billable' => $cnhRequest->billable,
                'price' => number_format($cnhRequest->price, 2),
                'requested_at' => $cnhRequest->created_at->format('Y-m-d\TH:i:s.vP'),
                'elapsed_time_in_milliseconds' => $elapsedTime,
                'remote_ip' => $cnhRequest->remote_ip,
                'signature' => base64_encode(encrypt($cnhRequest->id)),
            ],
            'data_count' => 1,
            'data' => [
                [
                    'id' => $cnhResponse->id,
                    'categoria' => $cnhResponse->categoria,
                    'codigo_seguranca' => $cnhResponse->codigo_seguranca,
                    'cpf' => $cnhResponse->cpf,
                    'emissao_data' => $cnhResponse->emissao_data->format('d/m/Y'),
                    'espelho' => $cnhResponse->espelho,
                    'mae' => $cnhResponse->mae,
                    'nome' => $cnhResponse->nome,
                    'nome_condutor_identico_ao_informado' => $cnhResponse->nome_condutor_identico_ao_informado,
                    'nome_mae_identico_ao_informado' => $cnhResponse->nome_mae_identico_ao_informado,
                    'registro' => $cnhResponse->registro,
                    'situacao' => $cnhResponse->situacao,
                    'validade_data' => $cnhResponse->validade_data->format('d/m/Y'),
                ]
            ],
            'site_receipts' => []
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/v2/senatran/validar-cnh",
     *     summary="Validar CNH",
     *     description="Valida os dados de uma CNH comparando com os registros cadastrados no banco de dados. Verifica CPF, registro, código de segurança, nome do condutor e nome da mãe.",
     *     operationId="validarCnh",
     *     tags={"CNH"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da CNH a ser validada",
     *         @OA\JsonContent(
     *             required={"token","cpf","registro","codigo_seguranca"},
     *             @OA\Property(property="token", type="string", example="seu_token_aqui", description="Token de autenticação da API"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-01", description="CPF do condutor"),
     *             @OA\Property(property="registro", type="string", example="11111111111", description="Número de registro da CNH"),
     *             @OA\Property(property="codigo_seguranca", type="string", example="12345678901", description="Código de segurança da CNH"),
     *             @OA\Property(property="nome_condutor", type="string", example="João da Silva", description="Nome completo do condutor (opcional - usado para validação)"),
     *             @OA\Property(property="nome_mae", type="string", example="Maria da Silva", description="Nome completo da mãe (opcional - usado para validação)"),
     *             @OA\Property(property="login_cpf", type="string", example="123.456.789-01", description="CPF para login GOV.BR (opcional)"),
     *             @OA\Property(property="login_senha", type="string", example="senha123", description="Senha para login GOV.BR (opcional)"),
     *             @OA\Property(property="pkcs12_cert", type="string", description="Certificado digital PKCS12 encriptado (opcional)"),
     *             @OA\Property(property="pkcs12_pass", type="string", description="Senha do certificado digital (opcional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CNH válida - todos os dados conferem",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="code_message", type="string", example="A requisição foi processada com sucesso."),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string")),
     *             @OA\Property(
     *                 property="header",
     *                 type="object",
     *                 @OA\Property(property="api_version", type="string", example="v2"),
     *                 @OA\Property(property="service", type="string", example="senatran/validar-cnh"),
     *                 @OA\Property(property="billable", type="boolean", example=true),
     *                 @OA\Property(property="price", type="string", example="0.24"),
     *                 @OA\Property(property="elapsed_time_in_milliseconds", type="integer", example=250)
     *             ),
     *             @OA\Property(property="data_count", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="categoria", type="string", example="AB"),
     *                     @OA\Property(property="codigo_seguranca", type="string", example="12345678901"),
     *                     @OA\Property(property="cpf", type="string", example="123.456.789-01"),
     *                     @OA\Property(property="emissao_data", type="string", example="06/02/2018"),
     *                     @OA\Property(property="espelho", type="string", example="9876543210"),
     *                     @OA\Property(property="mae", type="string", example="Maria da Silva"),
     *                     @OA\Property(property="nome", type="string", example="João da Silva"),
     *                     @OA\Property(property="nome_condutor_identico_ao_informado", type="boolean", example=true),
     *                     @OA\Property(property="nome_mae_identico_ao_informado", type="boolean", example=true),
     *                     @OA\Property(property="registro", type="string", example="11111111111"),
     *                     @OA\Property(property="situacao", type="string", example="válida"),
     *                     @OA\Property(property="validade_data", type="string", example="05/02/2028")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados não conferem - CNH inválida",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="code_message", type="string", example="Dados informados não conferem com a CNH cadastrada."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="Código de segurança não confere")
     *             ),
     *             @OA\Property(property="data_count", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="situacao", type="string", example="inválida"),
     *                     @OA\Property(property="nome_condutor_identico_ao_informado", type="boolean", example=false),
     *                     @OA\Property(property="nome_mae_identico_ao_informado", type="boolean", example=false)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="CNH não encontrada no banco de dados",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="code_message", type="string", example="CNH não encontrada no banco de dados."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(type="string", example="A CNH com os dados informados não foi encontrada.")
     *             ),
     *             @OA\Property(property="data_count", type="integer", example=0),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="code_message", type="string", example="Dados de entrada inválidos."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function validarCnh(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'cpf' => 'required|string',
            'registro' => 'required|string',
            'codigo_seguranca' => 'required|string',
            'nome_condutor' => 'nullable|string',
            'nome_mae' => 'nullable|string',
            'login_cpf' => 'nullable|string',
            'login_senha' => 'nullable|string',
            'pkcs12_cert' => 'nullable|string',
            'pkcs12_pass' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'code_message' => 'Dados de entrada inválidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Buscar CNH no banco de dados
        $cnhExistente = CnhResponse::whereHas('request', function($query) use ($request) {
            $query->where('cpf', $request->cpf)
                  ->where('registro', $request->registro);
        })->with('request')->first();

        // Se não encontrou CNH no banco
        if (!$cnhExistente) {
            return response()->json([
                'code' => 404,
                'code_message' => 'CNH não encontrada no banco de dados.',
                'errors' => ['A CNH com os dados informados não foi encontrada.'],
                'header' => [
                    'api_version' => 'v2',
                    'service' => 'senatran/validar-cnh',
                    'parameters' => [
                        'cpf' => $request->cpf,
                        'registro' => $request->registro,
                        'codigo_seguranca' => $request->codigo_seguranca,
                    ],
                    'requested_at' => now()->format('Y-m-d\TH:i:s.vP'),
                    'remote_ip' => $request->ip(),
                ],
                'data_count' => 0,
                'data' => [],
                'site_receipts' => []
            ], 404);
        }

        // Validar dados
        $dadosValidos = true;
        $erros = [];

        // Validar código de segurança
        if ($cnhExistente->codigo_seguranca !== $request->codigo_seguranca) {
            $dadosValidos = false;
            $erros[] = 'Código de segurança não confere';
        }

        // Validar nome do condutor (se informado)
        $nomeCondutorIdentico = true;
        if ($request->nome_condutor) {
            $nomeCondutorIdentico = strtoupper(trim($cnhExistente->nome)) === strtoupper(trim($request->nome_condutor));
            if (!$nomeCondutorIdentico) {
                $dadosValidos = false;
                $erros[] = 'Nome do condutor não confere';
            }
        }

        // Validar nome da mãe (se informado)
        $nomeMaeIdentico = true;
        if ($request->nome_mae) {
            $nomeMaeIdentico = strtoupper(trim($cnhExistente->mae)) === strtoupper(trim($request->nome_mae));
            if (!$nomeMaeIdentico) {
                $dadosValidos = false;
                $erros[] = 'Nome da mãe não confere';
            }
        }

        // Determinar situação
        $situacao = $dadosValidos ? $cnhExistente->situacao : 'inválida';

        // Salvar requisição
        $cnhRequest = CnhRequest::create([
            'cpf' => $request->cpf,
            'registro' => $request->registro,
            'codigo_seguranca' => $request->codigo_seguranca,
            'login_cpf' => $request->login_cpf ?? null,
            'login_senha' => $request->login_senha ? bcrypt($request->login_senha) : null,
            'nome_condutor' => $request->nome_condutor ?? '',
            'nome_mae' => $request->nome_mae ?? '',
            'client_name' => 'Minha Empresa',
            'token_name' => $request->token,
            'billable' => true,
            'price' => 0.24,
            'remote_ip' => $request->ip(),
        ]);

        // Calcular tempo de execução
        $elapsedTime = (int)((microtime(true) - $startTime) * 1000);
        $cnhRequest->update(['elapsed_time_in_milliseconds' => $elapsedTime]);

        // Preparar resposta
        $code = $dadosValidos ? 200 : 400;
        $codeMessage = $dadosValidos 
            ? 'A requisição foi processada com sucesso.' 
            : 'Dados informados não conferem com a CNH cadastrada.';

        return response()->json([
            'code' => $code,
            'code_message' => $codeMessage,
            'errors' => $erros,
            'header' => [
                'api_version' => 'v2',
                'service' => 'senatran/validar-cnh',
                'parameters' => [
                    'cpf' => $request->cpf,
                    'registro' => $request->registro,
                    'codigo_seguranca' => $request->codigo_seguranca,
                    'login_cpf' => $request->login_cpf,
                    'login_senha' => $request->login_senha,
                    'nome_condutor' => $request->nome_condutor,
                    'nome_mae' => $request->nome_mae,
                ],
                'client_name' => $cnhRequest->client_name,
                'token_name' => $cnhRequest->token_name,
                'billable' => $cnhRequest->billable,
                'price' => number_format($cnhRequest->price, 2),
                'requested_at' => $cnhRequest->created_at->format('Y-m-d\TH:i:s.vP'),
                'elapsed_time_in_milliseconds' => $elapsedTime,
                'remote_ip' => $cnhRequest->remote_ip,
                'signature' => base64_encode(encrypt($cnhRequest->id)),
            ],
            'data_count' => 1,
            'data' => [
                [
                    'categoria' => $cnhExistente->categoria,
                    'codigo_seguranca' => $cnhExistente->codigo_seguranca,
                    'cpf' => $cnhExistente->cpf,
                    'emissao_data' => $cnhExistente->emissao_data->format('d/m/Y'),
                    'espelho' => $cnhExistente->espelho,
                    'mae' => $cnhExistente->mae,
                    'nome' => $cnhExistente->nome,
                    'nome_condutor_identico_ao_informado' => $nomeCondutorIdentico,
                    'nome_mae_identico_ao_informado' => $nomeMaeIdentico,
                    'registro' => $cnhExistente->registro,
                    'situacao' => $situacao,
                    'validade_data' => $cnhExistente->validade_data->format('d/m/Y'),
                ]
            ],
            'site_receipts' => []
        ], $code);
    }
}