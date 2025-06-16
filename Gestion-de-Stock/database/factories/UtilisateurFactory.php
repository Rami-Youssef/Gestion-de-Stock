<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utilisateur>
 */
class UtilisateurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'utilisateur' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'motdepasse' => bcrypt('password'),
            'role' => $this->faker->randomElement(['admin', 'utilisateur', 'gestionnaire']),
        ];
    }
}
