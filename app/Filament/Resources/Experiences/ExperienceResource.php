<?php

namespace App\Filament\Resources\Experiences;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\Experiences\Pages\ManageExperiences;
use App\Models\Experience;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $slug = 'experiences';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'company';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Toggle::make('is_current')->label('Current position')->columnSpanFull(),
                TextInput::make('company')->required()->maxLength(255),
                TextInput::make('company_url')
                    ->label('Company URL')
                    ->url()
                    ->maxLength(255),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("role.$locale")
                        ->label('Role')
                        ->required()
                        ->maxLength(255),
                    TextInput::make("location.$locale")
                        ->label('Location')
                        ->required()
                        ->maxLength(255),
                    TextInput::make("period.$locale")
                        ->label('Period')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('01.2022 – Present'),
                    Textarea::make("description.$locale")
                        ->label('Description')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
                Repeater::make('highlights')
                    ->schema([
                        Textarea::make('pl')->label('Polski')->required()->rows(3),
                        Textarea::make('en')->label('English')->required()->rows(3),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->reorderable()
                    ->addActionLabel('Add highlight')
                    ->columnSpanFull(),
                TagsInput::make('technologies')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role')->wrap(),
                TextColumn::make('company')->searchable()->wrap(),
                TextColumn::make('location')->searchable(),
                TextColumn::make('period')->searchable(),
                TextColumn::make('technologies')->badge()->limitList(3),
                IconColumn::make('is_current')->label('Current')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make()->mutateRecordDataUsing(LocaleTabs::fill()),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExperiences::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
