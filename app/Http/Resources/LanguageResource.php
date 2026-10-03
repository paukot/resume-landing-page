<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Language */
class LanguageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        return [
            'name' => $this->getTranslation('name', $locale),
            'level' => $this->getTranslation('level', $locale),
        ];
    }
}
