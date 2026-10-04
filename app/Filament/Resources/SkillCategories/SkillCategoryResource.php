<?php

namespace App\Filament\Resources\SkillCategories;

use App\Enums\SkillIcon;
use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\SkillCategories\Pages\ManageSkillCategories;
use App\Models\SkillCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SkillCategoryResource extends Resource
{
    protected static ?string $model = SkillCategory::class;

    protected static ?string $slug = 'skill-categories';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'icon';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('icon')
                    ->options(SkillIcon::class)
                    ->searchable()
                    ->required()
                    ->helperText(new HtmlString(
                        'Browse icon names on '
                        .'<a href="https://lucide.dev/icons/" target="_blank" rel="noopener noreferrer" class="underline">lucide.dev/icons</a>. '
                        .'Only icons from the list above can be selected.'
                    )),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("name.$locale")->label('Name')->required()->maxLength(255),
                ]),
                TagsInput::make('skills')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('icon'),
                TextColumn::make('skills')->badge()->limitList(5)->wrap(),
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
            'index' => ManageSkillCategories::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
