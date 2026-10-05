<?php

use App\Filament\Resources\QuickStats\QuickStatResource;
use App\Filament\Resources\QuickStats\Pages\ManageQuickStats;
use App\Models\QuickStat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(QuickStatResource::getUrl('index'))->assertRedirect();
    });

    it('renders the index page and table component', function () {
        $this->get(QuickStatResource::getUrl('index'))
            ->assertOk()
            ->assertSeeLivewire(ManageQuickStats::class);
    });
});

describe('table', function () {
    it('displays records in the table', function () {
        $stat = QuickStat::create([
            'value' => '3.5+',
            'label' => ['en' => 'Years of experience', 'pl' => 'Lata doświadczenia'],
            'description' => ['en' => 'Working as a backend developer', 'pl' => 'Praca jako programista backend'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageQuickStats::class)
            ->assertCanSeeTableRecords([$stat])
            ->assertTableColumnExists('value')
            ->assertTableColumnExists('label')
            ->assertTableColumnExists('description');
    });
});

describe('creation', function () {
    it('can create a new record via action', function () {
        Livewire::test(ManageQuickStats::class)
            ->mountAction('create')
            ->setActionData([
                'value' => '50+',
                'label' => ['en' => 'Projects', 'pl' => 'Projekty'],
                'description' => ['en' => 'Completed projects', 'pl' => 'Ukończone projekty'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified();

        $stat = QuickStat::where('value', '50+')->first();

        expect($stat)->not->toBeNull()
            ->and($stat->getTranslation('label', 'pl'))->toBe('Projekty')
            ->and($stat->getTranslation('description', 'en'))->toBe('Completed projects')
            ->and($stat->sort_order)->toBe(1);
    });

    it('calculates the next sort_order correctly', function () {
        QuickStat::create([
            'value' => '10',
            'label' => ['en' => 'Old', 'pl' => 'Stary'],
            'description' => ['en' => 'Desc', 'pl' => 'Opis'],
            'sort_order' => 5,
        ]);

        Livewire::test(ManageQuickStats::class)
            ->mountAction('create')
            ->setActionData([
                'value' => '99%',
                'label' => ['en' => 'Success rate', 'pl' => 'Wskaźnik sukcesu'],
                'description' => ['en' => 'D', 'pl' => 'D'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $newStat = QuickStat::where('value', '99%')->first();
        expect($newStat->sort_order)->toBe(6);
    });

    it('validates required fields', function () {
        Livewire::test(ManageQuickStats::class)
            ->mountAction('create')
            ->setActionData([
                'value' => null,
                'label' => ['en' => null, 'pl' => null],
                'description' => ['en' => null, 'pl' => null],
            ])
            ->callMountedAction()
            ->assertHasActionErrors([
                'value' => 'required',
                'label.en' => 'required',
                'label.pl' => 'required',
                'description.en' => 'required',
                'description.pl' => 'required',
            ]);
    });
});

describe('editing', function () {
    it('can edit an existing record', function () {
        $stat = QuickStat::create([
            'value' => '10',
            'label' => ['en' => 'Awards', 'pl' => 'Nagrody'],
            'description' => ['en' => 'Won', 'pl' => 'Zdobyte'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageQuickStats::class)
            ->mountTableAction('edit', $stat)
            ->setTableActionData([
                'value' => '15+',
                'label' => ['en' => 'Awards Won', 'pl' => 'Zdobyte Nagrody'],
                'description' => ['en' => 'Total won', 'pl' => 'Razem zdobyte'],
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $stat->refresh();

        expect($stat->value)->toBe('15+')             ->and($stat->getTranslation('label', 'en'))->toBe('Awards Won')
            ->and($stat->getTranslation('label', 'pl'))->toBe('Zdobyte Nagrody')
            ->and($stat->getTranslation('description', 'en'))->toBe('Total won');
    });
});

describe('deletion', function () {
    it('can delete a record via table action', function () {
        $stat = QuickStat::create([
            'value' => 'Delete Me',
            'label' => ['en' => 'L', 'pl' => 'L'],
            'description' => ['en' => 'D', 'pl' => 'D'],
        ]);

        Livewire::test(ManageQuickStats::class)
            ->assertTableActionExists('delete')
            ->mountTableAction('delete', $stat)
            ->callMountedTableAction()
            ->assertNotified();

        $this->assertModelMissing($stat);
    });

    it('can bulk delete records', function () {
        QuickStat::create(['value' => '1', 'label' => ['en' => 'L1', 'pl' => 'L1'], 'description' => ['en' => 'D1', 'pl' => 'D1'], 'sort_order' => 1]);
        QuickStat::create(['value' => '2', 'label' => ['en' => 'L2', 'pl' => 'L2'], 'description' => ['en' => 'D2', 'pl' => 'D2'], 'sort_order' => 2]);

        $stats = QuickStat::all();

        Livewire::test(ManageQuickStats::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', $stats);

        expect(QuickStat::count())->toBe(0);
    });
});
