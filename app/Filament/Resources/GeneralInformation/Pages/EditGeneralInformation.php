<?php

namespace App\Filament\Resources\GeneralInformation\Pages;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\GeneralInformation\GeneralInformationResource;
use App\Models\GeneralInformation;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditGeneralInformation extends EditRecord
{
    protected static string $resource = GeneralInformationResource::class;

    public function mount(int|string|null $record = null): void
    {
        $information = GeneralInformation::query()
            ->firstOrCreate(
                [],
                GeneralInformation::factory()->make()->toArray()
            );

        parent::mount($information->getKey());
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return LocaleTabs::fill()($data, $this->getRecord());
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string|Htmlable
    {
        return 'General Information';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
