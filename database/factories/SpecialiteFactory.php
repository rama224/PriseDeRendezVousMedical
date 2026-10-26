<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialiteFactory extends Factory
{
    public function definition()
    {
        return [
            'nom' => $this->faker->unique()->jobTitle(),
        ];
    }
}
