<?php

namespace App\Http\Resources;

use App\Models\QuickStat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QuickStat */
class QuickStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        return [
            'value' => (string) $this->value,
            'label' => $this->getTranslation('label', $locale),
            'description' => $this->getTranslation('description', $locale),
        ];
    }
}
