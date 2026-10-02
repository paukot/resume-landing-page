<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\Projects\Pages\ManageProjects;
use App\Models\Project;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $slug = 'projects';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                Toggle::make('featured'),
                TextInput::make('live_url')->label('Live URL')->url()->maxLength(255),
                TextInput::make('github_url')->label('GitHub URL')->url()->maxLength(255),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("tagline.$locale")->label('Tagline')->required()->maxLength(255),
                    TextInput::make("category.$locale")->label('Category')->required()->maxLength(255),
                    Textarea::make("description.$locale")->label('Description')->required()->rows(4)->columnSpanFull(),
                ]),
                Repeater::make('highlights')
                    ->schema([
                        TextInput::make('pl')->label('Polski')->required(),
                        TextInput::make('en')->label('English')->required(),
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
                TextColumn::make('title')->searchable(),
                TextColumn::make('category'),
                TextColumn::make('technologies')->badge()->limitList(3),
                IconColumn::make('featured')->boolean(),
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
            'index' => ManageProjects::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
