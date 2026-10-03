<?php

namespace App\Filament\Resources\QuickStats;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\QuickStats\Pages\ManageQuickStats;
use App\Models\QuickStat;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuickStatResource extends Resource
{
    protected static ?string $model = QuickStat::class;

    protected static ?string $slug = 'quick-stats';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'value';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns()
            ->components([
                TextInput::make('value')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Shown as-is, e.g. "3.5+"')
                    ->columnSpanFull(),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("label.$locale")
                        ->label('Label')
                        ->required()
                        ->maxLength(255),
                    TextInput::make("description.$locale")
                        ->label('Description')
                        ->required()
                        ->maxLength(255),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('value'),
                TextColumn::make('label'),
                TextColumn::make('description')->limit(60),
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
            'index' => ManageQuickStats::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
