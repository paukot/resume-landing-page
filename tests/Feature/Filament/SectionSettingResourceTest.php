<?php

use App\Enums\CacheKey;
use App\Filament\Resources\SectionSettings\Pages\EditSectionSetting;
use App\Filament\Resources\SectionSettings\SectionSettingResource;
use App\Models\SectionSetting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(SectionSettingResource::getUrl())->assertRedirect();
    });

    it('renders the edit form on the index page', function () {
        $this->get(SectionSettingResource::getUrl())
            ->assertOk()
            ->assertSeeLivewire(EditSectionSetting::class);
    });

    it('does not allow creating additional records', function () {
        expect(SectionSettingResource::canCreate())->toBeFalse();
    });

    it('only has the index page', function () {
        expect(array_keys(SectionSettingResource::getPages()))->toBe(['index']);
    });
});

describe('single record behaviour', function () {
    it('uses the single record on first visit', function () {
        $countBefore = SectionSetting::count();

        Livewire::test(EditSectionSetting::class)->assertOk();

        expect($countBefore)->toBe(1)
            ->and(SectionSetting::count())->toBe(1);
    });

    it('does not create duplicate records on save', function () {
        Livewire::test(EditSectionSetting::class)
            ->fillForm(['show_about' => false])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        Livewire::test(EditSectionSetting::class)
            ->call('save')
            ->assertHasNoFormErrors();

        expect(SectionSetting::count())->toBe(1)
            ->and(SectionSetting::first()->show_about)->toBeFalse();
    });
});

describe('form', function () {
    it('has all expected toggle fields', function () {
        Livewire::test(EditSectionSetting::class)
            ->assertFormFieldExists('show_about')
            ->assertFormFieldExists('show_experience')
            ->assertFormFieldExists('show_skills')
            ->assertFormFieldExists('show_education')
            ->assertFormFieldExists('show_projects')
            ->assertFormFieldExists('show_contact');
    });

    it('loads current settings into the form', function () {
        SectionSetting::current()->update([
            'show_about' => true,
            'show_experience' => false,
            'show_skills' => true,
            'show_education' => false,
            'show_projects' => true,
            'show_contact' => false,
        ]);

        Livewire::test(EditSectionSetting::class)
            ->assertSchemaStateSet([
                'show_about' => true,
                'show_experience' => false,
                'show_skills' => true,
                'show_education' => false,
                'show_projects' => true,
                'show_contact' => false,
            ]);
    });
});

describe('saving & cache', function () {
    it('persists changes when toggles are updated', function () {
        Livewire::test(EditSectionSetting::class)
            ->fillForm([
                'show_about' => false,
                'show_experience' => false,
                'show_skills' => false,
                'show_education' => false,
                'show_projects' => false,
                'show_contact' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $setting = SectionSetting::first();

        expect($setting->show_about)->toBeFalse()
            ->and($setting->show_experience)->toBeFalse()
            ->and($setting->show_skills)->toBeFalse()
            ->and($setting->show_education)->toBeFalse()
            ->and($setting->show_projects)->toBeFalse()
            ->and($setting->show_contact)->toBeFalse();
    });

    it('busts the cache key when saved', function () {
        Cache::put(CacheKey::SectionSettings->value, ['dummy' => true]);

        expect(Cache::has(CacheKey::SectionSettings->value))->toBeTrue();

        Livewire::test(EditSectionSetting::class)
            ->fillForm(['show_projects' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        expect(Cache::has(CacheKey::SectionSettings->value))->toBeFalse();
    });
});
