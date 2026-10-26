<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CentreFactory extends Factory
{
    public function definition()
    {
        return [
            'nom' => 'Centre ' . $this->faker->unique()->city(),
            'adresse' => $this->faker->streetAddress(),
            'telephone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}
