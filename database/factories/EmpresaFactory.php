<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'cnpj' => $this->faker->unique()->numerify('########0001##'),
            'user_id' => User::factory(), // Relaciona com um usuário
        ];
    }
}

