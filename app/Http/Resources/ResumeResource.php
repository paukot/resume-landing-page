<?php

namespace App\Http\Resources;

use App\Enums\CacheKey;
use App\Models\Education;
use App\Models\Experience;
use App\Models\GeneralInformation;
use App\Models\Language;
use App\Models\Project;
use App\Models\QuickStat;
use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Builds the full `ResumeData` payload (see resources/js/types/resume.ts).
 *
 * @mixin GeneralInformation
 */
class ResumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        $cv = $this->getTranslation('cv', $locale);

        return [
            'name' => $this->name,
            'title' => $this->getTranslation('title', $locale),
            'summary' => $this->getTranslation('summary', $locale),
            'intro' => $this->getTranslation('intro', $locale),
            'contact' => [
                'email' => $this->email,
                'phone' => $this->phone,
                'location' => $this->location,
                'linkedin' => $this->linkedin,
                'github' => $this->github,
            ],
            'cvPdfUrl' => filled($cv) ? Storage::disk('public')->url($cv) : '',
            'languages' => LanguageResource::collection(
                Language::hydrate(
                    (array) cache()->remember(
                        CacheKey::Languages->value,
                        now()->addDay(),
                        fn () => Language::query()->orderBy('sort_order')->get()->map->getAttributes()->all()
                    )
                )
            )->resolve(),
            'quickStats' => QuickStatResource::collection(
                QuickStat::hydrate(
                    (array)cache()->remember(
                        CacheKey::QuickStats->value,
                        now()->addDay(),
                        fn() => QuickStat::query()->orderBy('sort_order')->get()->map->getAttributes()->all()
                    )
                )
            )->resolve(),
            'experience' => ExperienceResource::collection(
                Experience::hydrate(
                    (array)cache()->remember(
                        CacheKey::Experiences->value,
                        now()->addDay(),
                        fn() => Experience::query()->orderBy('sort_order')->get()->map->getAttributes()->all()
                    )
                )
            )->resolve(),
            'education' => EducationResource::collection(
                Education::hydrate(
                    (array)cache()->remember(
                        CacheKey::Education->value,
                        now()->addDay(),
                        fn() => Education::query()->orderBy('sort_order')->get()->map->getAttributes()->all()
                    )
                )
            )->resolve(),
            'skillCategories' => SkillCategoryResource::collection(
                SkillCategory::hydrate(
                    (array)cache()->remember(
                        CacheKey::SkillCategories->value,
                        now()->addDay(),
                        fn() => SkillCategory::query()->orderBy('sort_order')->get()->map->getAttributes()->all()
                    )
                )
            )->resolve(),
            'projects' => ProjectResource::collection(
                Project::hydrate(
                    (array)cache()->remember(
                        CacheKey::Projects->value,
                        now()->addDay(),
                        fn() => Project::query()->orderBy('sort_order')->get()->map->getAttributes()->all()
                    )
                )
            )->resolve(),
        ];
    }
}
