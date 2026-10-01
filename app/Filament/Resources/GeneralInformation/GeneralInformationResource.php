<?php

namespace App\Filament\Resources\GeneralInformation;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\GeneralInformation\Pages\EditGeneralInformation;
use App\Models\GeneralInformation;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class GeneralInformationResource extends Resource
{
    protected static ?string $model = GeneralInformation::class;

    protected static ?string $slug = 'general-information';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('phone'),

                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("title.$locale")->label('Title')->required()->maxLength(255),
                    TextInput::make("summary.$locale")->label('Summary')->required()->maxLength(255),
                    FileUpload::make("cv.$locale")
                        ->label('CV')
                        ->required()
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/pdf']),
                ]),

                TextInput::make('email')->nullable()->email(),
                TextInput::make('location')->nullable()->maxLength(255),
                TextInput::make('linkedin')->nullable()->isUrl()->maxLength(255),
                TextInput::make('github')->nullable()->isUrl()->maxLength(255),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => EditGeneralInformation::route('/'),
        ];
    }
}
