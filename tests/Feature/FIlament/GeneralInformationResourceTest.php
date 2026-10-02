<?php

use App\Filament\Resources\GeneralInformation\GeneralInformationResource;
use App\Filament\Resources\GeneralInformation\Pages\EditGeneralInformation;
use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create());
});

describe('access to the category', function () {
    it('guest is redirected to login page', function () {
        auth()->logout();

        $this->get(GeneralInformationResource::getUrl())->assertRedirect();
    });

    it('edit form is present on the index page', function () {
        GeneralInformation::factory()->create();

        $this->get(GeneralInformationResource::getUrl())
            ->assertOk()
            ->assertSeeLivewire(EditGeneralInformation::class);
    });

    it('does not create additional records', function () {
        expect(GeneralInformationResource::canCreate())->toBeFalse();
    });

    it('only index page is present', function () {
        expect(array_keys(GeneralInformationResource::getPages()))->toBe(['index']);
    });
});

describe('single record behaviour', function () {
    it('record is already created before a first visit', function () {
        $countBeforeFirstVisit = GeneralInformation::count();

        Livewire::test(EditGeneralInformation::class)->assertOk();

        $countAfterFirstVisit = GeneralInformation::count();

        expect($countBeforeFirstVisit)->toBe(1)
            ->and($countAfterFirstVisit)->toBe(1);
    });

    it('does not create duplicate records on save', function () {
        $user = User::factory()->create();
        $changedName = 'Test Name 123';

        $component = Livewire::actingAs($user)
            ->test(EditGeneralInformation::class)
            ->fillForm(GeneralInformation::factory()->make(['name' => $changedName])->toArray())
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $component->call('save')->assertHasNoFormErrors();

        expect(GeneralInformation::count())->toBe(1)
            ->and(GeneralInformation::first()->name)->toBe($changedName);
    });
});

describe('form', function () {
    it('loads every locale into the translation tabs', function () {
        GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->assertSchemaStateSet([
                'name' => 'Jane Doe',
                'title' => ['pl' => 'Programista Backend', 'en' => 'Backend Developer'],
                'summary' => ['pl' => 'Opis po polsku', 'en' => 'English summary'],
                'email' => 'jane@example.com',
                'phone' => '+48 123 456 789',
                'location' => 'Test City',
                'linkedin' => 'https://linkedin.com/in/jane',
                'github' => 'https://github.com/jane',
            ]);
    });

    it('has all expected fields', function () {
        GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('phone')
            ->assertFormFieldExists('title.pl')
            ->assertFormFieldExists('title.en')
            ->assertFormFieldExists('summary.pl')
            ->assertFormFieldExists('summary.en')
            ->assertFormFieldExists('cv.pl')
            ->assertFormFieldExists('cv.en')
            ->assertFormFieldExists('email')
            ->assertFormFieldExists('location')
            ->assertFormFieldExists('linkedin')
            ->assertFormFieldExists('github');
    });
});

describe('saving', function () {
    it('updates the record including both translations', function () {
        $information = GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'name' => 'John Smith',
                'phone' => '+48 999 888 777',
                'title' => ['pl' => 'Starszy Programista', 'en' => 'Senior Developer'],
                'summary' => ['pl' => 'Nowy opis', 'en' => 'New summary'],
                'email' => 'john@example.com',
                'location' => 'Kraków',
                'linkedin' => 'https://linkedin.com/in/john',
                'github' => 'https://github.com/john',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $information->refresh();

        expect($information->name)->toBe('John Smith')
            ->and($information->phone)->toBe('+48 999 888 777')
            ->and($information->getTranslation('title', 'pl'))->toBe('Starszy Programista')
            ->and($information->getTranslation('title', 'en'))->toBe('Senior Developer')
            ->and($information->getTranslation('summary', 'pl'))->toBe('Nowy opis')
            ->and($information->getTranslation('summary', 'en'))->toBe('New summary')
            ->and($information->email)->toBe('john@example.com')
            ->and($information->location)->toBe('Kraków')
            ->and($information->linkedin)->toBe('https://linkedin.com/in/john')
            ->and($information->github)->toBe('https://github.com/john');

        expect(GeneralInformation::count())->toBe(1);
    });

    it('keeps the stored cv files when saving other fields', function () {
        $information = GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm(['name' => 'Only Name Changed'])
            ->call('save')
            ->assertHasNoFormErrors();

        $information->refresh();

        expect($information->getTranslation('cv', 'pl'))->toBe('cv/cv-pl.pdf')
            ->and($information->getTranslation('cv', 'en'))->toBe('cv/cv-en.pdf');

        Storage::disk('public')->assertExists('cv/cv-pl.pdf');
        Storage::disk('public')->assertExists('cv/cv-en.pdf');
    });

    it('allows the optional fields to be empty', function () {
        $information = GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'email' => null,
                'location' => null,
                'linkedin' => null,
                'github' => null,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $information->refresh();

        expect($information->email)->toBeNull()
            ->and($information->location)->toBeNull()
            ->and($information->linkedin)->toBeNull()
            ->and($information->github)->toBeNull();
    });
});

describe('validation', function () {
    it('requires name and both title and summary translations', function () {
        GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'name' => null,
                'title' => ['pl' => null, 'en' => null],
                'summary' => ['pl' => null, 'en' => null],
            ])
            ->call('save')
            ->assertHasFormErrors([
                'name' => 'required',
                'title.pl' => 'required',
                'title.en' => 'required',
                'summary.pl' => 'required',
                'summary.en' => 'required',
            ]);
    });

    it('validates email and url formats', function () {
        GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'email' => 'not-an-email',
                'linkedin' => 'not-a-url',
                'github' => 'also-not-a-url',
            ])
            ->call('save')
            ->assertHasFormErrors([
                'email' => 'email',
                'linkedin' => 'url',
                'github' => 'url',
            ]);
    });

    it('enforces max length on title and summary', function () {
        GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'title' => ['pl' => str_repeat('a', 256), 'en' => 'ok'],
                'summary' => ['pl' => 'ok', 'en' => str_repeat('a', 256)],
            ])
            ->call('save')
            ->assertHasFormErrors([
                'title.pl' => 'max',
                'summary.en' => 'max',
            ]);
    });

    it('does not persist anything when validation fails', function () {
        $information = GeneralInformation::factory()->create();

        Livewire::test(EditGeneralInformation::class)
            ->fillForm(['name' => null])
            ->call('save');

        expect($information->fresh()->name)->toBe('Jane Doe');
    });
});
