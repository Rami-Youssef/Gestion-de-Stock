<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->word,
            'reference' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'quantite' => $this->faker->numberBetween(0, 100),
            'prix' => $this->faker->randomFloat(2, 10, 500),
            'categorie_id' => Categorie::factory(),
        ];
    }
}
