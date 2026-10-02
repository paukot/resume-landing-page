<?php

namespace Database\Factories;

use App\Models\GeneralInformation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class GeneralInformationFactory extends Factory
{
    protected $model = GeneralInformation::class;

    public function definition(): array
    {
        return [
            'name' => 'Jane Doe',
            'title' => ['pl' => 'Programista Backend', 'en' => 'Backend Developer'],
            'summary' => ['pl' => 'Opis po polsku', 'en' => 'English summary'],
            'cv' => ['pl' => null, 'en' => null],
            'email' => 'jane@example.com',
            'phone' => '+48 123 456 789',
            'location' => 'Test City',
            'linkedin' => 'https://linkedin.com/in/jane',
            'github' => 'https://github.com/jane',
        ];
    }
}
