<?php

namespace App\Filament\Resources\Educations;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\Educations\Pages\ManageEducation;
use App\Models\Education;
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

class EducationResource extends Resource
{
    protected static ?string $model = Education::class;

    protected static ?string $slug = 'education';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('institution')->required()->maxLength(255),
                TextInput::make('institution_url')->label('Institution URL')->url()->maxLength(255),
                TextInput::make('period')->required()->maxLength(255)->placeholder('2018 – 2022'),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("degree.$locale")->label('Degree')->required()->maxLength(255),
                    TextInput::make("field.$locale")->label('Field of study')->required()->maxLength(255),
                    TextInput::make("location.$locale")->label('Location')->required()->maxLength(255),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('degree'),
                TextColumn::make('field'),
                TextColumn::make('institution')->searchable(),
                TextColumn::make('period'),
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
            'index' => ManageEducation::route('/'),
        ];
    }
}
