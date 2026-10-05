<?php

use App\Filament\Resources\Experiences\ExperienceResource;
use App\Filament\Resources\Experiences\Pages\ManageExperiences;
use App\Models\Experience;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(ExperienceResource::getUrl('index'))->assertRedirect();
    });

    it('renders the index page and table component', function () {
        $this->get(ExperienceResource::getUrl('index'))
            ->assertOk()
            ->assertSeeLivewire(ManageExperiences::class);
    });
});

describe('table', function () {
    it('displays records in the table', function () {
        Experience::query()->delete();

        $experience = Experience::create([
            'is_current' => true,
            'company' => 'Acme Corp',
            'role' => ['en' => 'Backend Developer', 'pl' => 'Programista Backend'],
            'location' => ['en' => 'Remote', 'pl' => 'Zdalnie'],
            'period' => ['en' => '2022 - Present', 'pl' => '2022 - Obecnie'],
            'technologies' => ['PHP', 'Laravel', 'Docker'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageExperiences::class)
            ->assertCanSeeTableRecords([$experience])
            ->assertTableColumnExists('role')
            ->assertTableColumnExists('company')
            ->assertTableColumnExists('location')
            ->assertTableColumnExists('period')
            ->assertTableColumnExists('technologies')
            ->assertTableColumnExists('is_current');
    });
});

describe('creation', function () {
    it('can create a new record via action', function () {
        Livewire::test(ManageExperiences::class)
            ->mountAction('create')
            ->setActionData([
                'is_current' => true,
                'company' => 'Tech Solutions',
                'company_url' => 'https://techsolutions.com',
                'role' => ['en' => 'Senior Dev', 'pl' => 'Starszy Programista'],
                'location' => ['en' => 'Warsaw', 'pl' => 'Warszawa'],
                'period' => ['en' => '01.2023 – Present', 'pl' => '01.2023 – Obecnie'],
                'description' => ['en' => 'Built APIs.', 'pl' => 'Tworzono API.'],
                'technologies' => ['PHP 8', 'MySQL', 'Redis'],
                'highlights' => [
                    'item1' => [
                        'en' => 'Reduced load time by 50%.',
                        'pl' => 'Skrócono czas ładowania o 50%.',
                    ],
                ],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified();

        expect(Experience::count())->toBe(1);

        $experience = Experience::first();
        expect($experience->company)->toBe('Tech Solutions')
            ->and($experience->is_current)->toBeTrue()
            ->and($experience->getTranslation('role', 'pl'))->toBe('Starszy Programista')
            ->and($experience->technologies)->toBe(['PHP 8', 'MySQL', 'Redis'])
            ->and($experience->sort_order)->toBe(1);

        $highlights = collect($experience->highlights)->first();
        expect($highlights['en'])->toBe('Reduced load time by 50%.')
            ->and($highlights['pl'])->toBe('Skrócono czas ładowania o 50%.');
    });

    it('calculates the next sort_order correctly', function () {
        Experience::create([
            'company' => 'Previous Co',
            'role' => ['en' => 'Dev', 'pl' => 'Dev'],
            'location' => ['en' => 'Loc', 'pl' => 'Loc'],
            'period' => ['en' => '2020', 'pl' => '2020'],
            'sort_order' => 10,
        ]);

        Livewire::test(ManageExperiences::class)
            ->mountAction('create')
            ->setActionData([
                'company' => 'New Co',
                'role' => ['en' => 'Dev2', 'pl' => 'Dev2'],
                'location' => ['en' => 'Loc2', 'pl' => 'Loc2'],
                'period' => ['en' => '2021', 'pl' => '2021'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $newExp = Experience::where('company', 'New Co')->first();
        expect($newExp->sort_order)->toBe(11);
    });

    it('validates required fields', function () {
        Livewire::test(ManageExperiences::class)
            ->mountAction('create')
            ->setActionData([
                'company' => null,
                'role' => ['en' => null, 'pl' => null],
                'location' => ['en' => null, 'pl' => null],
                'period' => ['en' => null, 'pl' => null],
            ])
            ->callMountedAction()
            ->assertHasActionErrors([
                'company' => 'required',
                'role.en' => 'required',
                'role.pl' => 'required',
                'location.en' => 'required',
                'location.pl' => 'required',
                'period.en' => 'required',
                'period.pl' => 'required',
            ]);
    });
});

describe('editing', function () {
    it('can edit an existing record and modify JSON arrays', function () {
        $undoFakeRepeater = Repeater::fake();
        $experience = Experience::create([
            'is_current' => false,
            'company' => 'Old Corp',
            'role' => ['en' => 'Junior Dev', 'pl' => 'Młodszy Programista'],
            'location' => ['en' => 'Remote', 'pl' => 'Zdalnie'],
            'period' => ['en' => '2021 - 2022', 'pl' => '2021 - 2022'],
            'technologies' => ['HTML', 'CSS'],
            'highlights' => [
                ['en' => 'Old highlight', 'pl' => 'Stary punkt'],
            ],
            'sort_order' => 1,
        ]);

        $newName = 'New Name Corp';
        $newEnRole = 'Mid Dev';
        Livewire::test(ManageExperiences::class)
            ->callTableAction('edit', $experience, data: [
                'is_current' => true,
                'company' => 'New Name Corp',
                'role' => ['en' => 'Mid Dev', 'pl' => 'Mid Programista'],
                'location' => ['en' => 'Office', 'pl' => 'Biuro'],
                'period' => ['en' => '2022 - Present', 'pl' => '2022 - Obecnie'],
                'technologies' => ['HTML', 'CSS', 'JavaScript'],
                'highlights' => [
                    ['en' => 'Old highlight updated', 'pl' => 'Stary punkt zaktualizowany'],
                    ['en' => 'New highlight added', 'pl' => 'Nowy punkt dodany'],
                ],
            ])
            ->assertHasNoTableActionErrors()
            ->assertNotified();
        $experience->refresh();

        expect($experience->is_current)->toBeTrue()
            ->and($experience->company)->toBe('New Name Corp')
            ->and($experience->getTranslation('role', 'en'))->toBe('Mid Dev')
            ->and($experience->technologies)->toHaveCount(3)->toContain('JavaScript')
            ->and($experience->highlights)->toHaveCount(2);

        $undoFakeRepeater();
    });
});

describe('deletion', function () {
    it('can delete a record via table action', function () {
        $experience = Experience::create([
            'company' => 'To Be Deleted',
            'role' => ['en' => 'Dev', 'pl' => 'Dev'],
            'location' => ['en' => 'Loc', 'pl' => 'Loc'],
            'period' => ['en' => '2020', 'pl' => '2020'],
        ]);

        Livewire::test(ManageExperiences::class)
            ->assertTableActionExists('delete')
            ->mountTableAction('delete', $experience)
            ->callMountedTableAction()
            ->assertNotified();

        $this->assertModelMissing($experience);
    });

    it('can bulk delete records', function () {
        $exp1 = Experience::create(['company' => 'C1', 'role' => ['en' => 'r', 'pl' => 'r'], 'location' => ['en' => 'l', 'pl' => 'l'], 'period' => ['en' => 'p', 'pl' => 'p']]);
        $exp2 = Experience::create(['company' => 'C2', 'role' => ['en' => 'r', 'pl' => 'r'], 'location' => ['en' => 'l', 'pl' => 'l'], 'period' => ['en' => 'p', 'pl' => 'p']]);

        Livewire::test(ManageExperiences::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', [$exp1, $exp2]);

        $this->assertModelMissing($exp1);
        $this->assertModelMissing($exp2);
    });
});
