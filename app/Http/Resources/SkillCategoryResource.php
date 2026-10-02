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
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'skills' => $this->skills ?? [],
        ];
    }
}
