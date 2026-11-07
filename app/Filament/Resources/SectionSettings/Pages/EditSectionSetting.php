<?php

namespace App\Filament\Resources\SectionSettings\Pages;

use App\Filament\Resources\SectionSettings\SectionSettingResource;
use App\Models\SectionSetting;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditSectionSetting extends EditRecord
{
    protected static string $resource = SectionSettingResource::class;

    public function mount(int|string|null $record = null): void
    {
        $setting = SectionSetting::current();

        parent::mount($setting->getKey());
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Section Visibility Settings';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
