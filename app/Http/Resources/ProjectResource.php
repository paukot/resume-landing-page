<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = session('locale');

        $highlights = collect($this->highlights ?? [])->select($locale)->flatten()->toArray();

        $galleryUrls = collect($this->gallery ?? [])
            ->filter()
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->values()
            ->all();

        $rawContent = $this->getTranslation('content', $locale);
        $content = filled($rawContent) ? trim(Str::markdown((string) $rawContent)) : null;

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'tagline' => $this->getTranslation('tagline', $locale),
            'category' => $this->getTranslation('category', $locale),
            'description' => $this->getTranslation('description', $locale),
            'content' => $content,
            'coverImageUrl' => filled($this->cover_image) ? Storage::disk('public')->url($this->cover_image) : null,
            'galleryUrls' => $galleryUrls,
            'highlights' => $highlights,
            'technologies' => $this->technologies ?? [],
            'liveUrl' => $this->when(filled($this->live_url), $this->live_url),
            'githubUrl' => $this->when(filled($this->github_url), $this->github_url),
            'featured' => (bool) $this->featured,
        ];
    }
}
