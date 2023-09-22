<?php

namespace Database\Factories;

use App\Enums\Locale;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'country' => fake()->countryCode(),
            'locale' => Locale::random(),
        ];
    }
}
