<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\LocalizesPairs;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    use LocalizesPairs;

    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'title' => $this->title,
            'tagline' => $this->tagline,
            'category' => $this->category,
            'description' => $this->description,
            'highlights' => $this->localizedPairs($this->highlights),
            'technologies' => $this->technologies ?? [],
            'liveUrl' => $this->when(filled($this->live_url), $this->live_url),
            'githubUrl' => $this->when(filled($this->github_url), $this->github_url),
            'featured' => (bool) $this->featured,
        ];
    }
}
