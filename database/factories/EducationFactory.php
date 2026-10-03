<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<Education> */
class EducationFactory extends Factory
{
    protected $model = Education::class;

    public function definition(): array
    {
        return [
            'degree' => [
                'en' => $this->faker->words(asText: true),
                'pl' => $this->faker->words(asText: true),
            ],
            'field' => [
                'en' => $this->faker->words(asText: true),
                'pl' => $this->faker->words(asText: true),
            ],
            'institution' => [
                'en' => $this->faker->words(6, true),
                'pl' => $this->faker->words(6, true),
            ],
            'institution_url' => [
                'en' => $this->faker->url(),
                'pl' => $this->faker->url(),
            ],
            'location' => [
                'en' => $this->faker->city(),
                'pl' => $this->faker->city(),
            ],
            'period' => $this->faker->date('Y').' - '.$this->faker->date('Y'),
            'sort_order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
