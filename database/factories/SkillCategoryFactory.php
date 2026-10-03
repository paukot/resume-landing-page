<?php

namespace Database\Factories;

use App\Models\SkillCategory;
use Database\Factories\Concerns\HasTechnologies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<SkillCategory> */
class SkillCategoryFactory extends Factory
{
    use HasTechnologies;

    protected $model = SkillCategory::class;

    private static array $icons = [
        'database',
        'layers',
        'cpu',
        'code',
    ];

    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->words($this->faker->numberBetween(1, 3), true),
                'pl' => $this->faker->words($this->faker->numberBetween(1, 3), true),
            ],
            'icon' => self::$icons[$this->faker->numberBetween(0, count(self::$icons) - 1)],
            'skills' => $this->generateTechnologies($this->faker->numberBetween(3, 6)),
            'sort_order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
