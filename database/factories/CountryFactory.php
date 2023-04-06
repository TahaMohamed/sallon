<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $country_data = [];
        foreach (config('translatable.locales') as $locale) {
            $country_data +=[
                $locale => [
                    'name' => fake()->name(),
                    'description' => fake()->paragraph(2)
                ]
            ];
        }
        return $country_data;
    }

}
