<?php

namespace App\Filament\Resources\Educations\Pages;

use App\Filament\Resources\Educations\EducationResource;
use App\Models\Education;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEducation extends ManageRecords
{
    protected static string $resource = EducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->mutateDataUsing(fn (array $data): array => $data + [
                'sort_order' => (int) Education::max('sort_order') + 1,
            ]),
        ];
    }
}
