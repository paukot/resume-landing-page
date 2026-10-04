<?php

namespace App\Services;

use App\Enums\CacheKey;
use App\Models\Education;
use App\Models\Experience;
use App\Models\GeneralInformation;
use App\Models\Language;
use App\Models\Project;
use App\Models\QuickStat;
use App\Models\SkillCategory;
use Illuminate\Database\Eloquent\Collection;

class WelcomeService
{

    public function getCombinedData(): GeneralInformation
    {
        $information = $this->getGeneralInformation();

        $information->setRelations([
            'languages' => $this->getLanguages(),
            'quickStats' => $this->getQuickStats(),
            'experiences' => $this->getExperiences(),
            'educations' => $this->getEducations(),
            'skillCategories' => $this->getSkillCategories(),
            'projects' => $this->getProjects(),
        ]);

        return $information;
    }

    public function getGeneralInformation(): GeneralInformation
    {
        $information = cache()->remember(
            CacheKey::GeneralInformation->value,
            now()->addDay(),
            fn () => GeneralInformation::query()->first()?->getAttributes()
        );

        return (new GeneralInformation)->newFromBuilder($information);
    }

    public function getLanguages(): Collection
    {
        return Language::hydrate(
            (array) cache()->remember(
                CacheKey::Languages->value,
                now()->addDay(),
                fn () => Language::query()
                    ->orderBy('sort_order')
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all()
            )
        );
    }

    public function getQuickStats(): Collection
    {
        return QuickStat::hydrate(
            (array) cache()->remember(
                CacheKey::QuickStats->value,
                now()->addDay(),
                fn () => QuickStat::query()
                    ->orderBy('sort_order')
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all()
            )
        );
    }

    public function getExperiences(): Collection
    {
        return Experience::hydrate(
            (array) cache()->remember(
                CacheKey::Experiences->value,
                now()->addDay(),
                fn () => Experience::query()
                    ->orderBy('sort_order')
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all()
            )
        );
    }

    public function getEducations(): Collection
    {
        return Education::hydrate(
            (array) cache()->remember(
                CacheKey::Education->value,
                now()->addDay(),
                fn () => Education::query()
                    ->orderBy('sort_order')
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all()
            )
        );
    }

    public function getSkillCategories(): Collection
    {
        return SkillCategory::hydrate(
            (array) cache()->remember(
                CacheKey::SkillCategories->value,
                now()->addDay(),
                fn () => SkillCategory::query()
                    ->orderBy('sort_order')
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all()
            )
        );
    }

    public function getProjects(): Collection
    {
        return Project::hydrate(
            (array) cache()->remember(
                CacheKey::Projects->value,
                now()->addDay(),
                fn () => Project::query()
                    ->orderByDesc('featured')
                    ->orderBy('sort_order')
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all()
            )
        );
    }
}
