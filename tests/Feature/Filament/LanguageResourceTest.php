<?php

use App\Filament\Resources\Languages\LanguageResource;
use App\Filament\Resources\Languages\Pages\ManageLanguages;
use App\Models\Language;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(LanguageResource::getUrl('index'))->assertRedirect();
    });

    it('renders the index page and table component', function () {
        $this->get(LanguageResource::getUrl('index'))
            ->assertOk()
            ->assertSeeLivewire(ManageLanguages::class);
    });
});

describe('table', function () {
    it('displays records in the table', function () {
        Language::query()->delete();

        $language = Language::create([
            'name' => ['en' => 'English', 'pl' => 'Angielski'],
            'level' => ['en' => 'Advanced', 'pl' => 'Zaawansowany'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageLanguages::class)
            ->assertCanSeeTableRecords([$language])
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('level');
    });
});

describe('creation', function () {
    it('can create a new record via action', function () {
        Livewire::test(ManageLanguages::class)
            ->mountAction('create')
            ->setActionData([
                'name' => ['en' => 'Polish', 'pl' => 'Polski'],
                'level' => ['en' => 'Native', 'pl' => 'Ojczysty'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified();

        expect(Language::count())->toBe(1);

        $language = Language::first();
        expect($language->getTranslation('name', 'en'))->toBe('Polish')
            ->and($language->getTranslation('name', 'pl'))->toBe('Polski')
            ->and($language->getTranslation('level', 'en'))->toBe('Native')
            ->and($language->getTranslation('level', 'pl'))->toBe('Ojczysty')
            ->and($language->sort_order)->toBe(1);
    });

    it('calculates the next sort_order correctly', function () {
        Language::create([
            'name' => ['en' => 'Spanish', 'pl' => 'Hiszpański'],
            'level' => ['en' => 'Basic', 'pl' => 'Podstawowy'],
            'sort_order' => 3,
        ]);

        Livewire::test(ManageLanguages::class)
            ->mountAction('create')
            ->setActionData([
                'name' => ['en' => 'French', 'pl' => 'Francuski'],
                'level' => ['en' => 'Intermediate', 'pl' => 'Średniozaawansowany'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $newLanguage = Language::where('name->en', 'French')->first();
        expect($newLanguage->sort_order)->toBe(4);
    });

    it('validates required fields', function () {
        Livewire::test(ManageLanguages::class)
            ->mountAction('create')
            ->setActionData([
                'name' => ['en' => null, 'pl' => null],
                'level' => ['en' => null, 'pl' => null],
            ])
            ->callMountedAction()
            ->assertHasActionErrors([
                'name.en' => 'required',
                'name.pl' => 'required',
                'level.en' => 'required',
                'level.pl' => 'required',
            ]);
    });
});

describe('editing', function () {
    it('can edit an existing record', function () {
        $language = Language::create([
            'name' => ['en' => 'German', 'pl' => 'Niemiecki'],
            'level' => ['en' => 'B1', 'pl' => 'B1'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageLanguages::class)
            ->mountTableAction('edit', $language)
            ->setTableActionData([
                'name' => ['en' => 'German', 'pl' => 'Niemiecki'],
                'level' => ['en' => 'B2', 'pl' => 'B2'],
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $language->refresh();

        expect($language->getTranslation('level', 'en'))->toBe('B2')
            ->and($language->getTranslation('level', 'pl'))->toBe('B2');
    });
});

describe('deletion', function () {
    it('can delete a record via table action', function () {
        $language = Language::create([
            'name' => ['en' => 'Italian', 'pl' => 'Włoski'],
            'level' => ['en' => 'Basic', 'pl' => 'Podstawowy'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageLanguages::class)
            ->assertTableActionExists('delete')
            ->mountTableAction('delete', $language)
            ->callMountedTableAction()
            ->assertNotified();

        $this->assertModelMissing($language);
    });

    it('can bulk delete records', function () {
        Language::query()->delete();

        $lang1 = Language::create(['name' => ['en' => 'L1', 'pl' => 'L1'], 'level' => ['en' => 'A1', 'pl' => 'A1'], 'sort_order' => 1]);
        $lang2 = Language::create(['name' => ['en' => 'L2', 'pl' => 'L2'], 'level' => ['en' => 'A2', 'pl' => 'A2'], 'sort_order' => 2]);

        Livewire::test(ManageLanguages::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', [$lang1, $lang2]);

        $this->assertModelMissing($lang1);
        $this->assertModelMissing($lang2);
    });
});
