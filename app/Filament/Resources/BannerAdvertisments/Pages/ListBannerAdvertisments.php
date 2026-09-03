<?php

namespace App\Filament\Resources\BannerAdvertisments\Pages;

use App\Filament\Resources\BannerAdvertisments\BannerAdvertismentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBannerAdvertisments extends ListRecords
{
    protected static string $resource = BannerAdvertismentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
