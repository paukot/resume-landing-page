<?php

namespace Database\Factories\Concerns;

trait HasHighlights
{
    private function generateHighlights($amount): array
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
