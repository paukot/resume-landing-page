<?php

namespace App\Filament\Fields;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class LocaleTabs
{
    public const LOCALES = ['pl' => 'Polski', 'en' => 'English'];

    /** @param Closure(string $locale): array $fields */
    public static function make(Closure $fields): Tabs
    {
        return Tabs::make('Translations')
            ->tabs(array_map(
                fn (string $locale, string $name): Tab => Tab::make($name)->schema($fields($locale)),
                array_keys(self::LOCALES),
                self::LOCALES,
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
