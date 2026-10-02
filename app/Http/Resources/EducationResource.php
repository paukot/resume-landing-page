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
        return [
            'id' => (string) $this->id,
            'degree' => $this->degree,
            'field' => $this->field,
            'institution' => $this->institution,
            'institutionUrl' => $this->when(filled($this->institution_url), $this->institution_url),
            'location' => $this->location,
            'period' => $this->period,
        ];
    }
}
