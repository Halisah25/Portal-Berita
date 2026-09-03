<?php

namespace App\Filament\Resources\ArticleNews\Pages;

use App\Filament\Resources\ArticleNews\ArticleNewsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticleNews extends ListRecords
{
    protected static string $resource = ArticleNewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
