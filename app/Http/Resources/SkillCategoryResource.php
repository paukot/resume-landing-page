<?php

namespace App\Http\Resources;

use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SkillCategory */
class SkillCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale),
            'icon' => $this->icon,
            'skills' => $this->skills ?? [],
        ];
    }
}
