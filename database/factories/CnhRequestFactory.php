<?php

namespace Database\Factories;

use App\Models\CnhRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CnhRequestFactory extends Factory
{
    protected $model = CnhRequest::class;

    public function definition(): array
    {
        return [
            'cpf' => $this->faker->numerify('###.###.###-##'),
            'registro' => $this->faker->numerify('###########'),
            'codigo_seguranca' => $this->faker->numerify('###########'),
            'login_cpf' => $this->faker->optional()->numerify('###.###.###-##'),
            'login_senha' => $this->faker->optional()->password(),
            'nome_condutor' => $this->faker->name(),
            'nome_mae' => $this->faker->name('female'),
            'client_name' => $this->faker->company(),
            'token_name' => 'Token ' . $this->faker->word(),
            'billable' => $this->faker->boolean(80),
            'price' => 0.24,
            'remote_ip' => $this->faker->ipv4(),
            'elapsed_time_in_milliseconds' => $this->faker->numberBetween(500, 2000),
        ];
    }
}