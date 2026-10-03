<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Experience;
use App\Models\GeneralInformation;
use App\Models\Language;
use App\Models\Project;
use App\Models\QuickStat;
use App\Models\SkillCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        GeneralInformation::factory()->create();

        QuickStat::factory(fake()->numberBetween(2, 4))->create();
        Experience::factory(fake()->numberBetween(1, 4))->create();
        Education::factory(fake()->numberBetween(1, 2))->create();
        SkillCategory::factory(4)->create();
        Language::factory(fake()->numberBetween(2,4))->create();
        Project::factory(fake()->numberBetween(2, 4))->create();
    }
}
