<?php

namespace Database\Factories\Concerns;

trait HasHighlights
{
    /**
     * @return list<array<string, string>>
     */
    private function generateHighlights(int $amount = 3): array
    {
        $highlights = [];

        for ($i = 0; $i < $amount; $i++) {
            $highlights[] = [
                'en' => $this->faker->text($this->faker->numberBetween(32, 72)),
                'pl' => $this->faker->text($this->faker->numberBetween(32, 72)),
            ];
        }

        return $highlights;
    }
}
