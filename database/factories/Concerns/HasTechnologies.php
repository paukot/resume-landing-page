<?php

namespace Database\Factories\Concerns;

trait HasTechnologies
{
    /**
     * @return list<string>
     */
    private function generateTechnologies(int $amount = 3): array
    {
        $technologies = [];

        for ($i = 0; $i < $amount; $i++) {
            $words = $this->faker->words($this->faker->numberBetween(1, 3));
            $technologies[] = is_array($words) ? implode(' ', $words) : (string) $words;
        }

        return $technologies;
    }
}
