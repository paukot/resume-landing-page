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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $slug = 'projects';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Toggle::make('featured')->columnSpanFull(),
                TextInput::make('live_url')
                    ->label('Live URL')
                    ->url()
                    ->maxLength(255),
                TextInput::make('github_url')
                    ->label('GitHub URL')
                    ->url()
                    ->maxLength(255),
                Section::make('Visual Media')
                    ->description('Cover image for portfolio cards and screenshots for the detail modal.')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Primary Cover Image')
                            ->image()
                            ->disk('public')
                            ->directory('projects/covers')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->maxSize(20480)
                            ->openable()
                            ->downloadable()
                            ->helperText('Main thumbnail displayed on the landing page card (recommended 16:9, max 20MB).')
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Screenshots & Diagrams Gallery')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->panelLayout('grid')
                            ->image()
                            ->disk('public')
                            ->directory('projects/gallery')
                            ->visibility('public')
                            ->maxParallelUploads(2)
                            ->maxFiles(20)
                            ->maxSize(20480)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif'])
                            ->openable()
                            ->downloadable()
                            ->imageEditor()
                            ->helperText('Screenshots and architecture diagrams displayed in the detail modal. Drag to reorder, up to 20 images (max 20MB each).')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("title.$locale")
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make("tagline.$locale")
                        ->label('Tagline')
                        ->required()
                        ->maxLength(255),
                    TextInput::make("category.$locale")
                        ->label('Category')
                        ->required()
                        ->maxLength(255),
                    Textarea::make("description.$locale")
                        ->label('Short Card Summary')
                        ->required()
                        ->rows(3)
                        ->helperText('Brief overview displayed on the portfolio card.')
                        ->columnSpanFull(),
                    MarkdownEditor::make("content.$locale")
                        ->label('Detailed Case Study / Story')
                        ->nullable()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'strike',
                            'link',
                            'heading',
                            'blockquote',
                            'codeBlock',
                            'bulletList',
                            'orderedList',
                            'table',
                            'undo',
                            'redo',
                        ])
                        ->helperText('Supports Markdown syntax and rich tables shown in the project detail modal.')
                        ->columnSpanFull(),
                ]),
                Repeater::make('highlights')
                    ->schema([
                        Textarea::make('pl')->label('Polski')->required()->rows(2),
                        Textarea::make('en')->label('English')->required()->rows(2),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->reorderable()
                    ->collapsible()
                    ->addActionLabel('Add highlight')
                    ->columnSpanFull(),
                TagsInput::make('technologies')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(null),
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('category')->wrap(),
                TextColumn::make('github_url')->wrap(),
                TextColumn::make('technologies')->badge()->limitList(3)->wrap(),
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
