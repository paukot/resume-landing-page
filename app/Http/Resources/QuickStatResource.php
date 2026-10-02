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
        return [
            'value' => $this->value,
            'label' => $this->label,
            'description' => $this->description,
        ];
    }
}
