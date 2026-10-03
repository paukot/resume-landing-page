<?php

namespace App\Http\Resources;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Education */
class EducationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        return [
            'id' => $this->id,
            'degree' => $this->getTranslation('degree', $locale),
            'field' => $this->getTranslation('field', $locale),
            'institution' => $this->getTranslation('institution', $locale),
            'institutionUrl' => $this->getTranslation('institution_url', $locale),
            'location' => $this->getTranslation('location', $locale),
            'period' => $this->period,
        ];
    }
}
