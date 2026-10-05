<?php

use App\Filament\Resources\GeneralInformation\GeneralInformationResource;
use App\Filament\Resources\GeneralInformation\Pages\EditGeneralInformation;
use App\Models\GeneralInformation;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(GeneralInformationResource::getUrl())->assertRedirect();
    });

    it('renders the edit form on the index page', function () {
        $this->get(GeneralInformationResource::getUrl())
            ->assertOk()
            ->assertSeeLivewire(EditGeneralInformation::class);
    });

    it('does not allow creating additional records', function () {
        expect(GeneralInformationResource::canCreate())->toBeFalse();
    });

    it('only has the index page', function () {
        expect(array_keys(GeneralInformationResource::getPages()))->toBe(['index']);
    });
});

describe('single record behaviour', function () {
    it('uses the migration-seeded record on first visit', function () {
        $countBefore = GeneralInformation::count();

        Livewire::test(EditGeneralInformation::class)->assertOk();

        expect($countBefore)->toBe(1)
            ->and(GeneralInformation::count())->toBe(1);
    });

    it('does not create duplicate records on save', function () {
        Livewire::test(EditGeneralInformation::class)
            ->fillForm(['name' => 'Updated Name'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        Livewire::test(EditGeneralInformation::class)
            ->call('save')
            ->assertHasNoFormErrors();

        expect(GeneralInformation::count())->toBe(1)
            ->and(GeneralInformation::first()->name)->toBe('Updated Name');
    });
});

describe('form', function () {
    it('loads the migration-seeded data including translations into the form', function () {
        Livewire::test(EditGeneralInformation::class)
            ->assertSchemaStateSet([
                'name' => 'Ange Doe',
                'title' => ['en' => 'Biologist', 'pl' => 'Biologist'],
                'intro' => ['en' => 'intro text', 'pl' => 'intro text'],
                'summary' => ['en' => 'summary text', 'pl' => 'summary text'],
                'email' => 'angnedoe@example.com',
                'phone' => '+12 123 456 789',
                'location' => 'New york',
                'linkedin' => 'https://www.linkedin.com/in/ange-doe',
                'github' => 'https://github.com/ange-doe',
            ]);
    });

    it('has all expected fields', function () {
        Livewire::test(EditGeneralInformation::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('phone')
            ->assertFormFieldExists('title.pl')
            ->assertFormFieldExists('title.en')
            ->assertFormFieldExists('intro.pl')
            ->assertFormFieldExists('intro.en')
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
        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'name' => 'John Smith',
                'phone' => '+48 999 888 777',
                'title' => ['pl' => 'Starszy Programista', 'en' => 'Senior Developer'],
                'intro' => ['pl' => 'Nowy intro', 'en' => 'New intro'],
                'summary' => ['pl' => 'Nowy opis', 'en' => 'New summary'],
                'email' => 'john@example.com',
                'location' => 'Kraków',
                'linkedin' => 'https://linkedin.com/in/john',
                'github' => 'https://github.com/john',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $information = GeneralInformation::first();

        expect($information->name)->toBe('John Smith')
            ->and($information->phone)->toBe('+48 999 888 777')
            ->and($information->getTranslation('title', 'pl'))->toBe('Starszy Programista')
            ->and($information->getTranslation('title', 'en'))->toBe('Senior Developer')
            ->and($information->getTranslation('intro', 'pl'))->toBe('Nowy intro')
            ->and($information->getTranslation('intro', 'en'))->toBe('New intro')
            ->and($information->getTranslation('summary', 'pl'))->toBe('Nowy opis')
            ->and($information->getTranslation('summary', 'en'))->toBe('New summary')
            ->and($information->email)->toBe('john@example.com')
            ->and($information->location)->toBe('Kraków')
            ->and($information->linkedin)->toBe('https://linkedin.com/in/john')
            ->and($information->github)->toBe('https://github.com/john');

        expect(GeneralInformation::count())->toBe(1);
    });

    it('allows nullable fields to be empty', function () {
        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'location' => null,
                'github' => null,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $information = GeneralInformation::first();

        expect($information->location)->toBeNull()
            ->and($information->github)->toBeNull();
    });
});

describe('validation', function () {
    it('requires name, phone, email, linkedin and both title, intro, summary translations', function () {
        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'name' => null,
                'phone' => null,
                'email' => null,
                'linkedin' => null,
                'title' => ['pl' => null, 'en' => null],
                'intro' => ['pl' => null, 'en' => null],
                'summary' => ['pl' => null, 'en' => null],
            ])
            ->call('save')
            ->assertHasFormErrors([
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'linkedin' => 'required',
                'title.pl' => 'required',
                'title.en' => 'required',
                'intro.pl' => 'required',
                'intro.en' => 'required',
                'summary.pl' => 'required',
                'summary.en' => 'required',
            ]);
    });

    it('validates email and url formats', function () {
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

    it('enforces max length on title, intro, and summary', function () {
        Livewire::test(EditGeneralInformation::class)
            ->fillForm([
                'title' => ['pl' => str_repeat('a', 256), 'en' => 'ok'],
                'intro' => ['pl' => 'ok', 'en' => str_repeat('a', 513)],
                'summary' => ['pl' => str_repeat('a', 1201), 'en' => 'ok'],
            ])
            ->call('save')
            ->assertHasFormErrors([
                'title.pl' => 'max',
                'intro.en' => 'max',
                'summary.pl' => 'max',
            ]);
    });

    it('does not persist changes when validation fails', function () {
        Livewire::test(EditGeneralInformation::class)
            ->fillForm(['name' => null])
            ->call('save');

        expect(GeneralInformation::first()->name)->toBe('Ange Doe');
    });
});
