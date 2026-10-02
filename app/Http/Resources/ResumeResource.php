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
        $contact = [
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'location' => (string) $this->location,
            'linkedin' => (string) $this->linkedin,
        ];

        if (filled($this->github)) {
            $contact['github'] = $this->github;
        }

        $cv = $this->cv;

        return [
            'name' => $this->name,
            'title' => $this->title,
            'summary' => $this->summary,
            'status' => [
                'available' => (bool) $this->is_available,
                'text' => (string) $this->status_text,
            ],
            'contact' => $contact,
            'cvPdfUrl' => filled($cv) ? Storage::disk('public')->url($cv) : '',
            'languages' => LanguageResource::collection(
                Language::query()->orderBy('sort_order')->get()
            ),
            'quickStats' => QuickStatResource::collection(
                QuickStat::query()->orderBy('sort_order')->get()
            ),
            'experience' => ExperienceResource::collection(
                Experience::query()->orderBy('sort_order')->get()
            ),
            'education' => EducationResource::collection(
                Education::query()->orderBy('sort_order')->get()
            ),
            'skillCategories' => SkillCategoryResource::collection(
                SkillCategory::query()->orderBy('sort_order')->get()
            ),
            'projects' => ProjectResource::collection(
                Project::query()->orderBy('sort_order')->get()
            ),
        ];
    }
}
