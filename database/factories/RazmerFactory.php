<?php

namespace Database\Factories;

use App\Models\Razmer;
use Illuminate\Database\Eloquent\Factories\Factory;

class RazmerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Razmer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'marka_id' => $this->faker->numberBetween(1,5),
            'title' => $this->faker->sentence,
            'price' => $this->faker->word,
            'image' => $this->faker->word,
            'description' => $this->faker->sentence,
            'url' => $this->faker->sentence,
        ];
    }
}
