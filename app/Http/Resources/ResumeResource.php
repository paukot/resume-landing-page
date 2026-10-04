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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Builds the full `ResumeData` payload (see resources/js/types/resume.ts).
 *
 * @mixin GeneralInformation
 * @property-read Collection<int, Language> $languages
 * @property-read Collection<int, QuickStat> $quickStats
 * @property-read Collection<int, Experience> $experiences
 * @property-read Collection<int, Education> $educations
 * @property-read Collection<int, SkillCategory> $skillCategories
 * @property-read Collection<int, Project> $projects
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
            'languages' => LanguageResource::collection($this->languages)->resolve(),
            'quickStats' => QuickStatResource::collection($this->quickStats)->resolve(),
            'experience' => ExperienceResource::collection($this->experiences)->resolve(),
            'education' => EducationResource::collection($this->educations)->resolve(),
            'skillCategories' => SkillCategoryResource::collection($this->skillCategories)->resolve(),
            'projects' => ProjectResource::collection($this->projects)->resolve(),
        ];
    }
}
