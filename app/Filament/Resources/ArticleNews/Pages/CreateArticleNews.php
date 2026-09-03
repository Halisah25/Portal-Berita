<?php

namespace App\Filament\Resources\ArticleNews\Pages;

use App\Filament\Resources\ArticleNews\ArticleNewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleNews extends CreateRecord
{
    protected static string $resource = ArticleNewsResource::class;
}
