<?php

namespace Database\Factories;

use App\Models\QuickStat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<QuickStat>
 */
class QuickStatFactory extends Factory
{
    protected $model = QuickStat::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->numberBetween(20, 100),
            'label' => $this->faker->words($this->faker->numberBetween(3, 6), true),
            'description' => $this->faker->text($this->faker->numberBetween(16, 48)),
            'sort_order' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
