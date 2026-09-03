<?php

namespace App\Filament\Resources\ArticleNews;

use App\Filament\Resources\ArticleNews\Pages\CreateArticleNews;
use App\Filament\Resources\ArticleNews\Pages\EditArticleNews;
use App\Filament\Resources\ArticleNews\Pages\ListArticleNews;
use App\Filament\Resources\ArticleNews\Schemas\ArticleNewsForm;
use App\Filament\Resources\ArticleNews\Tables\ArticleNewsTable;
use App\Models\ArticleNews;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleNewsResource extends Resource
{
    protected static ?string $model = ArticleNews::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    // FORM
    public static function form(Schema $schema): Schema
    {
        return ArticleNewsForm::configure($schema)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->required()
                    ->image()
                    ->acceptedFileTypes(['image/*']),

                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),

                Forms\Components\Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->required(),

                // <-- Perubahan: simpan kunci singkat ke DB (featured / not_featured)
                Forms\Components\Select::make('is_featured')
                    ->label('Is featured')
                    ->options([
                        'featured'     => 'Featured',
                        'not_featured' => 'Not Featured',
                    ])
                    ->default('not_featured')
                    ->required(),

                Forms\Components\RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    // TABLE
    public static function table(Table $table): Table
    {
        return ArticleNewsTable::configure($table)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('name')
                    ->searchable(),

                // <-- Perubahan: cek kunci singkat, dan format label tampilannya
                Tables\Columns\TextColumn::make('is_featured')
                    ->label('Is Featured')
                    ->badge()
                    ->colors([
                        'success' => fn ($state) => (string)$state === 'featured',
                        'danger'  => fn ($state) => (string)$state === 'not_featured',
                    ])
                    ->formatStateUsing(
                        fn ($state) => match ((string)$state) {
                            'featured' => 'Featured',
                            'not_featured' => 'Not Featured',
                            // jika ada data lama seperti 'Featured' atau 'Not Featured', fallback:
                            'Featured' => 'Featured',
                            'Not Featured' => 'Not Featured',
                            default => ucfirst(str_replace('_', ' ', (string)$state)),
                        }
                    ),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticleNews::route('/'),
            'create' => CreateArticleNews::route('/create'),
            'edit' => EditArticleNews::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}