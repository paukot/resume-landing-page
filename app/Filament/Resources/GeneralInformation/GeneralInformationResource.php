<?php

namespace App\Filament\Resources\GeneralInformation;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\GeneralInformation\Pages\EditGeneralInformation;
use App\Models\GeneralInformation;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
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

    protected static ?string $modelLabel = 'General Information';

    protected static ?string $pluralModelLabel = 'General Information';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('phone')->required(),

                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("title.$locale")
                        ->label('Title')
                        ->required()
                        ->maxLength(255),
                    FileUpload::make("cv.$locale")
                        ->label('CV')
                        ->nullable()
                        ->disk('public')
                        ->directory("cv\{$locale}")
                        ->visibility('public')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/pdf']),
                    Textarea::make("intro.$locale")
                        ->label('Intro')
                        ->required()
                        ->maxLength(512)
                        ->rows(2),
                    Textarea::make("summary.$locale")
                        ->label('Summary')
                        ->required()
                        ->maxLength(1200)
                        ->rows(3),
                ])->columns(2),

                TextInput::make('email')->required()->email(),
                TextInput::make('location')->nullable()->maxLength(255),
                TextInput::make('linkedin')
                    ->required()
                    ->url()
                    ->maxLength(255),
                TextInput::make('github')
                    ->nullable()
                    ->url()
                    ->maxLength(255),
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
