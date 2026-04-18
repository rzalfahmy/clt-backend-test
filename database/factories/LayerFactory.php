<?php

namespace Database\Factories;

use App\Models\Layer;
use App\Models\Layup;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayerFactory extends Factory
{
    protected $model = Layer::class;

    public function definition(): array
    {
        return [
            'layup_id' => Layup::factory(),
            'layer_order' => fake()->numberBetween(1, 8),
            'thickness' => fake()->randomFloat(2, 0.5, 4),
            'width' => fake()->randomFloat(2, 20, 80),
            'angle' => fake()->randomElement([-45, 0, 45, 90]),
        ];
    }
}
