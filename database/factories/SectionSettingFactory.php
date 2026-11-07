<?php

namespace Database\Factories;

use App\Models\SectionSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SectionSetting>
 */
class SectionSettingFactory extends Factory
{
    protected $model = SectionSetting::class;

    public function definition(): array
    {
        return [
            'show_about' => true,
            'show_experience' => true,
            'show_skills' => true,
            'show_education' => true,
            'show_projects' => true,
            'show_contact' => true,
        ];
    }
}
