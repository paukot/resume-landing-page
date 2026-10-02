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
        $locales = config('app.locales');

        return Tabs::make('Translations')
            ->tabs(array_map(
                fn (string $locale, string $name): Tab => Tab::make($name)->schema($fields($locale)),
                array_keys($locales),
                $locales,
            ))
            ->columnSpanFull();
    }

    public static function fill(): Closure
    {
        return function (array $data, Model $record): array {
            foreach ($record->getTranslatableAttributes() as $attribute) {
                $data[$attribute] = $record->getTranslations($attribute);
            }

            return $data;
        };
    }
}
