<?php

namespace Database\Factories;

use App\Models\Marka;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Marka::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'marka_id' => $this->faker->numberBetween(1, 5),
            'subcategory_id' => 3204,
            'title' => $this->faker->sentence(15),
            'price' => $this->faker->word,
            'description' => $this->faker->sentence(20),
            'image' => $this->faker->url,
            'url' => $this->faker->url,

        ];
    }
}
