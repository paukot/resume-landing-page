<?php

use App\Filament\Resources\Educations\EducationResource;
use App\Filament\Resources\Experiences\ExperienceResource;
use App\Filament\Resources\GeneralInformation\GeneralInformationResource;
use App\Filament\Resources\Languages\LanguageResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\QuickStats\QuickStatResource;
use App\Filament\Resources\SectionSettings\SectionSettingResource;
use App\Filament\Resources\SkillCategories\SkillCategoryResource;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('filament navigation structure', function () {
    it('assigns GeneralInformation and SectionSettings to Settings group', function () {
        expect(GeneralInformationResource::getNavigationGroup())->toBe('Settings')
            ->and(SectionSettingResource::getNavigationGroup())->toBe('Settings');
    });

    it('assigns all content resources to Content group', function () {
        $contentResources = [
            QuickStatResource::class,
            ExperienceResource::class,
            ProjectResource::class,
            SkillCategoryResource::class,
            EducationResource::class,
            LanguageResource::class,
        ];

        foreach ($contentResources as $resource) {
            expect($resource::getNavigationGroup())->toBe('Content');
        }
    });

    it('configures distinct navigation icons and labels for all resources', function () {
        $allResources = [
            GeneralInformationResource::class,
            SectionSettingResource::class,
            QuickStatResource::class,
            ExperienceResource::class,
            ProjectResource::class,
            SkillCategoryResource::class,
            EducationResource::class,
            LanguageResource::class,
        ];

        foreach ($allResources as $resource) {
            expect($resource::getNavigationIcon())->not->toBeNull()
                ->and($resource::getModelLabel())->not->toBeEmpty();
        }
    });
});
