<?php

namespace Database\Factories;

use App\Models\Project;
use Database\Factories\Concerns\HasHighlights;
use Database\Factories\Concerns\HasTechnologies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<\App\Models\Project> */
class ProjectFactory extends Factory
{
    use HasTechnologies, HasHighlights;

    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'title' => [
                'en' => $this->faker->words($this->faker->numberBetween(4, 10), true),
                'pl' => $this->faker->words($this->faker->numberBetween(4, 10), true),
            ],
            'tagline' => [
                'en' => $this->faker->words($this->faker->numberBetween(2, 5), true),
                'pl' => $this->faker->words($this->faker->numberBetween(2, 5), true),
            ],
            'category' => [
                'en' => $this->faker->words($this->faker->numberBetween(1, 3), true),
                'pl' => $this->faker->words($this->faker->numberBetween(1, 3), true),
            ],
            'description' => [
                'en' => $this->faker->paragraphs($this->faker->numberBetween(2, 4), true),
                'pl' => $this->faker->paragraphs($this->faker->numberBetween(2, 4), true),
            ],
            'highlights' => $this->generateHighlights($this->faker->numberBetween(3, 5)),
            'technologies' => $this->generateTechnologies($this->faker->numberBetween(3, 5)),
            'live_url' => $this->faker->url(),
            'github_url' => $this->faker->url(),
            'featured' => $this->faker->boolean(),
            'sort_order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
