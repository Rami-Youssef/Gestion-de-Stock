<?php

namespace Database\Factories;

use App\Models\Produit;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MouvementStock>
 */
class MouvementStockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date_cmd = $this->faker->dateTimeBetween('-1 month', 'now');
        $type = $this->faker->randomElement(['entrée', 'sortie']);
        
        return [
            'type' => $type,
            'quantite' => $this->faker->numberBetween(1, 20),
            'date_cmd' => $date_cmd,
            'date_reception' => $type === 'entrée' ? $this->faker->optional(0.8)->dateTimeBetween($date_cmd, 'now') : null,
            'produit_id' => Produit::factory(),
            'utilisateur_id' => Utilisateur::factory(),
        ];
    }
}
