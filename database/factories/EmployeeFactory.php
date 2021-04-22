<?php

namespace Database\Factories;

use App\Models\Dependent;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

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
            'benefit_cost' => 1000 * $multiplier * 100,
        ];
    }
}
