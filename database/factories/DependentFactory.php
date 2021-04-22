<?php

namespace Database\Factories;

use App\Models\Dependent;
use Illuminate\Database\Eloquent\Factories\Factory;

class DependentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dependent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        $multiplier = strtoupper(substr($name, 0, 1)) === 'A' ? 0.9 : 1.0;

        return [
            'name' => $name,
            'relation' => $this->faker->randomElement(['spouse', 'child', 'other']),
        ];
    }
}
