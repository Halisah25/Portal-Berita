<?php

namespace App\Filament\Resources\BannerAdvertisments\Pages;

use App\Filament\Resources\BannerAdvertisments\BannerAdvertismentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBannerAdvertisment extends EditRecord
{
    protected static string $resource = BannerAdvertismentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
