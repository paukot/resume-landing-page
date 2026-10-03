<?php

namespace App\Http\Resources;

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

        $title = $this->getTranslation('title', $locale);
        $summary = $this->getTranslation('summary', $locale);
        $cv = $this->getTranslation('cv', $locale);

        return [
            'name' => $this->name,
            'title' => $title,
            'summary' => $summary,
//            TODO: check fields status
            'status' => [
                'available' => true,
                'text' => 'Available for opportunities',
            ],
            'contact' => [
                'email' => $this->email,
                'phone' => $this->phone,
                'location' => $this->location,
                'linkedin' => $this->linkedin,
                'github' => $this->github,
            ],
            'cvPdfUrl' => filled($cv) ? Storage::disk('public')->url($cv) : '',
            'languages' => LanguageResource::collection(
                Language::query()->orderBy('sort_order')->get()
            )->resolve(),
            'quickStats' => QuickStatResource::collection(
                QuickStat::query()->orderBy('sort_order')->get()
            )->resolve(),
            'experience' => ExperienceResource::collection(
                Experience::query()->orderBy('sort_order')->get()
            )->resolve(),
            'education' => EducationResource::collection(
                Education::query()->orderBy('sort_order')->get()
            )->resolve(),
            'skillCategories' => SkillCategoryResource::collection(
                SkillCategory::query()->orderBy('sort_order')->get()
            )->resolve(),
            'projects' => ProjectResource::collection(
                Project::query()->orderBy('sort_order')->get()
            )->resolve(),
        ];
    }
}
