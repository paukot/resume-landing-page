<?php

namespace App\Filament\Resources\GeneralInformation\Pages;

use App\Filament\Fields\LocaleTabs;
use App\Filament\Resources\GeneralInformation\GeneralInformationResource;
use App\Models\GeneralInformation;
use Filament\Resources\Pages\EditRecord;

class EditGeneralInformation extends EditRecord
{
    protected static string $resource = GeneralInformationResource::class;

    public function mount(int|string|null $record = null): void
    {
        $information = GeneralInformation::query()
            ->firstOrCreate([
                'name' => 'General Name',
                'title' => ['pl' => 'Title', 'en' => 'Title'],
                'summary' => ['pl' => 'Summary', 'en' => 'Summary'],
                'cv' => ['pl' => '', 'en' => ''],
        ]);

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
}
