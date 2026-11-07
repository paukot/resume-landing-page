<?php

namespace App\Filament\Fields;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class LocaleTabs
{
    public static function make(Closure $fields): Tabs
    {
        /** @var array<string, string> $locales */
        $locales = config('app.locales', []);

        return Tabs::make('Translations')
            ->tabs(array_map(
                fn (int|string $locale, string $name): Tab => Tab::make($name)->schema($fields((string) $locale)),
                array_keys($locales),
                $locales,
            ))
            ->columnSpanFull();
    }

    public static function fill(): Closure
    {
        return function (array $data, Model $record): array {
            if (! method_exists($record, 'getTranslatableAttributes') || ! method_exists($record, 'getTranslations')) {
                return $data;
            }

            /** @var list<string> $attributes */
            $attributes = $record->getTranslatableAttributes();

            foreach ($attributes as $attribute) {
                $data[$attribute] = $record->getTranslations($attribute);
            }

            return $data;
        };
    }
}
