<?php

namespace Database\Factories;

use App\Models\CnhResponse;
use App\Models\CnhRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CnhResponseFactory extends Factory
{
    protected $model = CnhResponse::class;

    public function definition(): array
    {
        $categorias = ['A', 'B', 'AB', 'C', 'D', 'E', 'AC', 'AD', 'AE'];
        $situacoes = ['válida', 'suspensa', 'cassada', 'vencida'];
        
        return [
            'cnh_request_id' => CnhRequest::factory(),
            'categoria' => $this->faker->randomElement($categorias),
            'codigo_seguranca' => $this->faker->numerify('###########'),
            'cpf' => $this->faker->numerify('###.###.###-##'),
            'emissao_data' => $this->faker->dateTimeBetween('-10 years', '-1 year'),
            'espelho' => $this->faker->numerify('##########'),
            'mae' => $this->faker->name('female'),
            'nome' => $this->faker->name(),
            'nome_condutor_identico_ao_informado' => $this->faker->boolean(90),
            'nome_mae_identico_ao_informado' => $this->faker->boolean(90),
            'registro' => $this->faker->numerify('###########'),
            'situacao' => $this->faker->randomElement($situacoes),
            'validade_data' => $this->faker->dateTimeBetween('now', '+5 years'),
        ];
    }
}