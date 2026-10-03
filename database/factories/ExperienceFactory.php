<?php

namespace Database\Factories;

use App\Models\Experience;
use Database\Factories\Concerns\HasHighlights;
use Database\Factories\Concerns\HasTechnologies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    use HasTechnologies, HasHighlights;

    protected $model = Experience::class;

    public function definition(): array
    {
        return [
            'role' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'company_url' => $this->faker->url(),
            'location' => $this->faker->city(),
            'period' => $this->faker->date().' - '.$this->faker->date(),
            'is_current' => false,
            'description' => $this->faker->text($this->faker->numberBetween(80, 140)),
            'highlights' => $this->generateHighlights($this->faker->numberBetween(3, 8)),
            'technologies' => $this->generateTechnologies($this->faker->numberBetween(2, 6)),
            'sort_order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
