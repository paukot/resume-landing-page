<?php

namespace App\Http\Resources\Concerns;

trait LocalizesPairs
{
    /**
     * Turns [{"pl": "...", "en": "..."}, ...] into string[] for the current locale.
     *
     * @param  array<int, array<string, string>>|null  $pairs
     * @return array<int, string>
     */
    protected function localizedPairs(?array $pairs): array
    {
        $locale = app()->getLocale();
        $fallback = config('app.fallback_locale');

        return collect($pairs ?? [])
            ->map(fn (array $pair): string => (string) ($pair[$locale] ?? $pair[$fallback] ?? ''))
            ->filter()
            ->values()
            ->all();
    }
}
