<?php

namespace App\Http\Resources;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Experience */
class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        $highlights = collect($this->highlights)->select($locale)->flatten()->toArray();

        return [
            'id' => $this->id,
            'role' => $this->getTranslation('role', $locale),
            'company' => $this->company,
            'companyUrl' => $this->when(filled($this->company_url), $this->company_url),
            'location' => $this->getTranslation('location', $locale),
            'period' => $this->getTranslation('period', $locale),
            'isCurrent' => (bool) $this->is_current,
            'description' => $this->getTranslation('description', $locale),
            'highlights' => $highlights,
            'technologies' => $this->technologies ?? [],
        ];
    }
}
