<?php

namespace Database\Seeders;

use App\Models\CnhRequest;
use App\Models\CnhResponse;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CnhSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            [
                'request' => [
                    'cpf' => '123.456.789-01',
                    'registro' => '11111111111',
                    'codigo_seguranca' => '12345678901',
                    'login_cpf' => '123.456.789-01',
                    'login_senha' => bcrypt('senha123'),
                    'nome_condutor' => 'João da Silva',
                    'nome_mae' => 'Maria da Silva',
                    'client_name' => 'Empresa Teste LTDA',
                    'token_name' => 'Token de Desenvolvimento',
                    'billable' => true,
                    'price' => 0.24,
                    'remote_ip' => '192.168.1.100',
                    'elapsed_time_in_milliseconds' => 1250,
                ],
                'response' => [
                    'categoria' => 'AB',
                    'codigo_seguranca' => '12345678901',
                    'cpf' => '123.456.789-01',
                    'emissao_data' => Carbon::parse('2018-06-15'),
                    'espelho' => '9876543210',
                    'mae' => 'Maria da Silva',
                    'nome' => 'João da Silva',
                    'nome_condutor_identico_ao_informado' => true,
                    'nome_mae_identico_ao_informado' => true,
                    'registro' => '11111111111',
                    'situacao' => 'válida',
                    'validade_data' => Carbon::parse('2028-06-15'),
                ],
            ],
            [
                'request' => [
                    'cpf' => '987.654.321-09',
                    'registro' => '22222222222',
                    'codigo_seguranca' => '98765432109',
                    'login_cpf' => null,
                    'login_senha' => null,
                    'nome_condutor' => 'Ana Paula Santos',
                    'nome_mae' => 'Rita Santos',
                    'client_name' => 'Sistema RH',
                    'token_name' => 'Token Produção',
                    'billable' => true,
                    'price' => 0.24,
                    'remote_ip' => '10.0.0.50',
                    'elapsed_time_in_milliseconds' => 980,
                ],
                'response' => [
                    'categoria' => 'B',
                    'codigo_seguranca' => '98765432109',
                    'cpf' => '987.654.321-09',
                    'emissao_data' => Carbon::parse('2020-03-20'),
                    'espelho' => '1234567890',
                    'mae' => 'Rita Santos',
                    'nome' => 'Ana Paula Santos',
                    'nome_condutor_identico_ao_informado' => true,
                    'nome_mae_identico_ao_informado' => true,
                    'registro' => '22222222222',
                    'situacao' => 'válida',
                    'validade_data' => Carbon::parse('2025-03-20'),
                ],
            ],
            [
                'request' => [
                    'cpf' => '111.222.333-44',
                    'registro' => '33333333333',
                    'codigo_seguranca' => '11122233344',
                    'login_cpf' => '111.222.333-44',
                    'login_senha' => bcrypt('senha456'),
                    'nome_condutor' => 'Carlos Eduardo Oliveira',
                    'nome_mae' => 'Joana Oliveira',
                    'client_name' => 'Auto Escola Central',
                    'token_name' => 'Token Teste',
                    'billable' => true,
                    'price' => 0.24,
                    'remote_ip' => '172.16.0.10',
                    'elapsed_time_in_milliseconds' => 1500,
                ],
                'response' => [
                    'categoria' => 'C',
                    'codigo_seguranca' => '11122233344',
                    'cpf' => '111.222.333-44',
                    'emissao_data' => Carbon::parse('2019-11-10'),
                    'espelho' => '5555555555',
                    'mae' => 'Joana Oliveira',
                    'nome' => 'Carlos Eduardo Oliveira',
                    'nome_condutor_identico_ao_informado' => true,
                    'nome_mae_identico_ao_informado' => true,
                    'registro' => '33333333333',
                    'situacao' => 'válida',
                    'validade_data' => Carbon::parse('2024-11-10'),
                ],
            ],
            [
                'request' => [
                    'cpf' => '444.555.666-77',
                    'registro' => '44444444444',
                    'codigo_seguranca' => '44455566677',
                    'login_cpf' => null,
                    'login_senha' => null,
                    'nome_condutor' => 'Mariana Costa',
                    'nome_mae' => 'Sandra Costa',
                    'client_name' => 'Transportadora XYZ',
                    'token_name' => 'Token API',
                    'billable' => false,
                    'price' => 0.00,
                    'remote_ip' => '192.168.0.200',
                    'elapsed_time_in_milliseconds' => 850,
                ],
                'response' => [
                    'categoria' => 'D',
                    'codigo_seguranca' => '44455566677',
                    'cpf' => '444.555.666-77',
                    'emissao_data' => Carbon::parse('2017-08-05'),
                    'espelho' => '7777777777',
                    'mae' => 'Sandra Costa',
                    'nome' => 'Mariana Costa',
                    'nome_condutor_identico_ao_informado' => true,
                    'nome_mae_identico_ao_informado' => true,
                    'registro' => '44444444444',
                    'situacao' => 'válida',
                    'validade_data' => Carbon::parse('2027-08-05'),
                ],
            ],
            [
                'request' => [
                    'cpf' => '555.666.777-88',
                    'registro' => '55555555555',
                    'codigo_seguranca' => '55566677788',
                    'login_cpf' => '555.666.777-88',
                    'login_senha' => bcrypt('senha789'),
                    'nome_condutor' => 'Pedro Henrique Alves',
                    'nome_mae' => 'Lucia Alves',
                    'client_name' => 'Consultas Online',
                    'token_name' => 'Token Homologação',
                    'billable' => true,
                    'price' => 0.24,
                    'remote_ip' => '10.10.10.10',
                    'elapsed_time_in_milliseconds' => 1100,
                ],
                'response' => [
                    'categoria' => 'E',
                    'codigo_seguranca' => '55566677788',
                    'cpf' => '555.666.777-88',
                    'emissao_data' => Carbon::parse('2021-01-15'),
                    'espelho' => '8888888888',
                    'mae' => 'Lucia Alves',
                    'nome' => 'Pedro Henrique Alves',
                    'nome_condutor_identico_ao_informado' => true,
                    'nome_mae_identico_ao_informado' => true,
                    'registro' => '55555555555',
                    'situacao' => 'válida',
                    'validade_data' => Carbon::parse('2026-01-15'),
                ],
            ],
            [
                'request' => [
                    'cpf' => '666.777.888-99',
                    'registro' => '66666666666',
                    'codigo_seguranca' => '66677788899',
                    'login_cpf' => null,
                    'login_senha' => null,
                    'nome_condutor' => 'Fernanda Lima',
                    'nome_mae' => 'Patricia Lima',
                    'client_name' => 'App Validação',
                    'token_name' => 'Token Mobile',
                    'billable' => true,
                    'price' => 0.24,
                    'remote_ip' => '200.150.100.50',
                    'elapsed_time_in_milliseconds' => 920,
                ],
                'response' => [
                    'categoria' => 'A',
                    'codigo_seguranca' => '66677788899',
                    'cpf' => '666.777.888-99',
                    'emissao_data' => Carbon::parse('2022-05-10'),
                    'espelho' => '3333333333',
                    'mae' => 'Patricia Lima',
                    'nome' => 'Fernanda Lima',
                    'nome_condutor_identico_ao_informado' => true,
                    'nome_mae_identico_ao_informado' => true,
                    'registro' => '66666666666',
                    'situacao' => 'válida',
                    'validade_data' => Carbon::parse('2027-05-10'),
                ],
            ],
        ];

        foreach ($requests as $data) {
            $request = CnhRequest::create($data['request']);
            
            $data['response']['cnh_request_id'] = $request->id;
            CnhResponse::create($data['response']);
        }

        $this->command->info('✅ Criados ' . count($requests) . ' registros de CNH com sucesso!');
    }
}