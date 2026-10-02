<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\LocalizesPairs;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Experience */
class ExperienceResource extends JsonResource
{
    use LocalizesPairs;

    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'role' => $this->role,
            'company' => $this->company,
            'companyUrl' => $this->when(filled($this->company_url), $this->company_url),
            'location' => $this->location,
            'period' => $this->period,
            'isCurrent' => (bool) $this->is_current,
            'description' => $this->when(filled($this->description), $this->description),
            'highlights' => $this->localizedPairs($this->highlights),
            'technologies' => $this->technologies ?? [],
        ];
    }
}
