<?php

namespace App\Filament\Resources\SectionSettings;

use App\Filament\Resources\SectionSettings\Pages\EditSectionSetting;
use App\Models\SectionSetting;
use BackedEnum;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SectionSettingResource extends Resource
{
    protected static ?string $model = SectionSetting::class;

    protected static ?string $slug = 'section-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static \UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'Section Visibility';

    protected static ?string $pluralModelLabel = 'Section Visibility';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Landing Page Sections Visibility')
                    ->description('Turn sections below the main hero view on or off. Changes will immediately reflect on the public landing page.')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                Toggle::make('show_about')
                                    ->label('About & Direct Channels')
                                    ->helperText('Displays the summary, intro, and direct social/email links.')
                                    ->default(true),

                                Toggle::make('show_experience')
                                    ->label('Work Experience')
                                    ->helperText('Displays career history, roles, and accomplishments.')
                                    ->default(true),

                                Toggle::make('show_skills')
                                    ->label('Skills')
                                    ->helperText('Displays categorized technical competencies and tags.')
                                    ->default(true),

                                Toggle::make('show_education')
                                    ->label('Education')
                                    ->helperText('Displays academic degrees, universities, and graduation dates.')
                                    ->default(true),

                                Toggle::make('show_projects')
                                    ->label('Projects & Architecture')
                                    ->helperText('Displays featured portfolio projects with live and code links.')
                                    ->default(true),

                                Toggle::make('show_contact')
                                    ->label('Contact')
                                    ->helperText('Displays the bottom get-in-touch callout and channel links.')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => EditSectionSetting::route('/'),
        ];
    }
}
