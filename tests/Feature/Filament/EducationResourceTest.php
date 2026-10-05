<?php

use App\Filament\Resources\Educations\EducationResource;
use App\Filament\Resources\Educations\Pages\ManageEducation;
use App\Models\Education;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(EducationResource::getUrl('index'))->assertRedirect();
    });

    it('renders the index page and table component', function () {
        $this->get(EducationResource::getUrl('index'))
            ->assertOk()
            ->assertSeeLivewire(ManageEducation::class);
    });
});

describe('table', function () {
    it('displays records in the table', function () {
        $education = Education::create([
            'institution' => ['en' => 'MIT', 'pl' => 'MIT'],
            'degree' => ['en' => 'BSc', 'pl' => 'Licencjat'],
            'field' => ['en' => 'Computer Science', 'pl' => 'Informatyka'],
            'location' => ['en' => 'Boston', 'pl' => 'Boston'],
            'period' => '2018 - 2022',
            'sort_order' => 1,
        ]);

        Livewire::test(ManageEducation::class)
            ->assertCanSeeTableRecords([$education])
            ->assertTableColumnExists('degree')
            ->assertTableColumnExists('field')
            ->assertTableColumnExists('institution')
            ->assertTableColumnExists('period');
    });
});

describe('creation', function () {
    it('can create a new record via action', function () {
        Livewire::test(ManageEducation::class)
            ->mountAction('create')
            ->setActionData([
                'institution' => ['en' => 'Harvard', 'pl' => 'Uniwersytet Harvarda'],
                'institution_url' => ['en' => 'https://harvard.edu', 'pl' => 'https://harvard.edu'],
                'degree' => ['en' => 'MSc', 'pl' => 'Magister'],
                'field' => ['en' => 'Physics', 'pl' => 'Fizyka'],
                'location' => ['en' => 'Cambridge', 'pl' => 'Cambridge'],
                'period' => '2020 - 2022',
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified();

        expect(Education::count())->toBe(1);

        $education = Education::first();
        expect($education->getTranslation('institution', 'en'))->toBe('Harvard')
            ->and($education->getTranslation('institution', 'pl'))->toBe('Uniwersytet Harvarda')
            ->and($education->period)->toBe('2020 - 2022')
            ->and($education->sort_order)->toBe(1); // Should calculate max(sort_order) + 1
    });

    it('calculates the next sort_order correctly when creating', function () {
        Education::create([
            'institution' => ['en' => 'First', 'pl' => 'Pierwszy'],
            'degree' => ['en' => 'D1', 'pl' => 'D1'],
            'field' => ['en' => 'F1', 'pl' => 'F1'],
            'location' => ['en' => 'L1', 'pl' => 'L1'],
            'period' => '2010',
            'sort_order' => 5, // Simulating an existing high sort_order
        ]);

        Livewire::test(ManageEducation::class)
            ->mountAction('create')
            ->setActionData([
                'institution' => ['en' => 'Second', 'pl' => 'Drugi'],
                'degree' => ['en' => 'D2', 'pl' => 'D2'],
                'field' => ['en' => 'F2', 'pl' => 'F2'],
                'location' => ['en' => 'L2', 'pl' => 'L2'],
                'period' => '2011',
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $newEducation = Education::where('period', '2011')->first();
        expect($newEducation->sort_order)->toBe(6); // 5 + 1
    });

    it('validates required fields during creation', function () {
        Livewire::test(ManageEducation::class)
            ->mountAction('create')
            ->setActionData([
                'institution' => ['en' => null, 'pl' => null],
                'degree' => ['en' => null, 'pl' => null],
                'field' => ['en' => null, 'pl' => null],
                'location' => ['en' => null, 'pl' => null],
                'period' => null,
            ])
            ->callMountedAction()
            ->assertHasActionErrors([
                'institution.en' => 'required',
                'institution.pl' => 'required',
                'degree.en' => 'required',
                'degree.pl' => 'required',
                'field.en' => 'required',
                'field.pl' => 'required',
                'location.en' => 'required',
                'location.pl' => 'required',
                'period' => 'required',
            ]);
    });
});

describe('editing', function () {
    it('can edit an existing record via table action', function () {
        $education = Education::create([
            'institution' => ['en' => 'MIT', 'pl' => 'MIT'],
            'degree' => ['en' => 'BSc', 'pl' => 'Licencjat'],
            'field' => ['en' => 'Computer Science', 'pl' => 'Informatyka'],
            'location' => ['en' => 'Boston', 'pl' => 'Boston'],
            'period' => '2018 - 2022',
            'sort_order' => 1,
        ]);

        Livewire::test(ManageEducation::class)
            ->mountTableAction('edit', $education)
            ->setTableActionData([
                'institution' => ['en' => 'Stanford', 'pl' => 'Stanford PL'],
                'degree' => ['en' => 'BSc', 'pl' => 'Licencjat'],
                'field' => ['en' => 'Math', 'pl' => 'Matematyka'],
                'location' => ['en' => 'CA', 'pl' => 'CA'],
                'period' => '2019 - 2023',
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $education->refresh();

        expect($education->getTranslation('institution', 'en'))->toBe('Stanford')
            ->and($education->getTranslation('field', 'pl'))->toBe('Matematyka')
            ->and($education->period)->toBe('2019 - 2023');
    });
});

describe('deletion', function () {
    it('can delete a record via table action', function () {
        $education = Education::create([
            'institution' => ['en' => 'MIT', 'pl' => 'MIT'],
            'degree' => ['en' => 'BSc', 'pl' => 'Licencjat'],
            'field' => ['en' => 'Computer Science', 'pl' => 'Informatyka'],
            'location' => ['en' => 'Boston', 'pl' => 'Boston'],
            'period' => '2018 - 2022',
            'sort_order' => 1,
        ]);

        Livewire::test(ManageEducation::class)
            ->assertTableActionExists('delete')
            ->mountTableAction('delete', $education)
            ->callMountedTableAction()
            ->assertNotified();

        $this->assertModelMissing($education);
    });

    it('can bulk delete records', function () {
        $edu1 = Education::create([
            'institution' => ['en' => 'One', 'pl' => 'Jeden'],
            'degree' => ['en' => 'D', 'pl' => 'D'],
            'field' => ['en' => 'F', 'pl' => 'F'],
            'location' => ['en' => 'L', 'pl' => 'L'],
            'period' => '2020',
            'sort_order' => 1,
        ]);

        $edu2 = Education::create([
            'institution' => ['en' => 'Two', 'pl' => 'Dwa'],
            'degree' => ['en' => 'D', 'pl' => 'D'],
            'field' => ['en' => 'F', 'pl' => 'F'],
            'location' => ['en' => 'L', 'pl' => 'L'],
            'period' => '2021',
            'sort_order' => 2,
        ]);

        Livewire::test(ManageEducation::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', [$edu1, $edu2]);

        $this->assertModelMissing($edu1);
        $this->assertModelMissing($edu2);
    });
});
