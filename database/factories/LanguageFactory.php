<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<Language> */
class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->word(),
                'pl' => $this->faker->word(),
            ],
            'level' => [
                'en' => $this->faker->words($this->faker->numberBetween(1, 2), true),
                'pl' => $this->faker->words($this->faker->numberBetween(1, 2), true),
            ],
            'sort_order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
