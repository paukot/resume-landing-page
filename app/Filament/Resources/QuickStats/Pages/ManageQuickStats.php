<?php

namespace App\Filament\Resources\QuickStats\Pages;

use App\Filament\Resources\QuickStats\QuickStatResource;
use App\Models\QuickStat;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageQuickStats extends ManageRecords
{
    protected static string $resource = QuickStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->mutateDataUsing(fn (array $data): array => $data + [
                'sort_order' => (int) QuickStat::max('sort_order') + 1,
            ]),
        ];
    }
}
