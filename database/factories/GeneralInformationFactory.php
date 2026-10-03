<?php

namespace Database\Factories;

use App\Models\GeneralInformation;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneralInformationFactory extends Factory
{
    protected $model = GeneralInformation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name().' '.$this->faker->lastname(),
            'title' => ['en' => $this->faker->jobTitle(), 'pl' => $this->faker->jobTitle()],
            'intro' => ['en' => $this->faker->text(160), 'pl' => $this->faker->text(160)],
            'summary' => ['en' => $this->faker->text(380), 'pl' => $this->faker->text(380)],
            'cv' => ['pl' => null, 'en' => null],
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'location' => $this->faker->city(),
            'linkedin' => $this->faker->url(),
            'github' => $this->faker->url(),
        ];
    }
}
