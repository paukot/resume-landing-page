<?php

namespace Database\Factories\Concerns;

trait HasTechnologies
{
    private function generateTechnologies($amount): array
    {
        $technologies = [];

        for ($i = 0; $i < $amount; $i++) {
            $technologies[] = $this->faker->words($this->faker->numberBetween(1, 3), true);
        }

        return $technologies;
    }
}
