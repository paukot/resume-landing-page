<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        $highlights = collect($this->highlights)->select($locale)->flatten()->toArray();

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'tagline' => $this->getTranslation('tagline', $locale),
            'category' => $this->getTranslation('category', $locale),
            'description' => $this->getTranslation('description', $locale),
            'highlights' => $highlights,
            'technologies' => $this->technologies ?? [],
            'liveUrl' => $this->when(filled($this->live_url), $this->live_url),
            'githubUrl' => $this->when(filled($this->github_url), $this->github_url),
            'featured' => (bool) $this->featured,
        ];
    }
}
