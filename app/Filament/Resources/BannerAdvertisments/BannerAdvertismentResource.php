<?php

namespace App\Filament\Resources\BannerAdvertisments;

use App\Filament\Resources\BannerAdvertisments\Pages\CreateBannerAdvertisment;
use App\Filament\Resources\BannerAdvertisments\Pages\EditBannerAdvertisment;
use App\Filament\Resources\BannerAdvertisments\Pages\ListBannerAdvertisments;
use App\Filament\Resources\BannerAdvertisments\Schemas\BannerAdvertismentForm;
use App\Filament\Resources\BannerAdvertisments\Tables\BannerAdvertismentsTable;
use App\Models\BannerAdvertisment;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerAdvertismentResource extends Resource
{
    protected static ?string $model = BannerAdvertisment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Banner Advertisment';

    /**
     * Form configuration
     */
    public static function form(Schema $schema): Schema
    {
        return BannerAdvertismentForm::configure($schema)
            ->schema([
                Forms\Components\TextInput::make('link')
                    ->label('URL')
                    ->activeUrl()
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Thumbnail Image')
                    ->image() // pastikan ini tetap agar preview muncul
                    ->disk('public') // gunakan disk publik
                    ->directory('banners') // simpan di storage/app/public/banners
                    ->visibility('public') // file bisa diakses
                    ->preserveFilenames() // pertahankan nama file asli
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp']) // tipe gambar yang valid
                    ->maxSize(4096) // maksimal 4MB
                    ->required(),

                Forms\Components\Select::make('is_active')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'not_active' => 'Not Active',
                    ])
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'banner' => 'Banner',
                        'square' => 'Square',
                    ])
                    ->required(),
            ]);
    }

    /**
     * Table configuration
     */
    public static function table(Table $table): Table
    {
        return BannerAdvertismentsTable::configure($table)
            ->columns([
                Tables\Columns\TextColumn::make('link')
                    ->label('URL')
                    ->searchable(),

                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'not_active' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public'),
            ])
            ->filters([]);
    }

    /**
     * Relations (none yet)
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Page routes
     */
    public static function getPages(): array
    {
        return [
            'index' => ListBannerAdvertisments::route('/'),
            'create' => CreateBannerAdvertisment::route('/create'),
            'edit' => EditBannerAdvertisment::route('/{record}/edit'),
        ];
    }

    /**
     * Handle soft deletes
     */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
