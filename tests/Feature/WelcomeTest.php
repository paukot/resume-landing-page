<?php

use App\Enums\CacheKey;
use App\Models\Project;
use App\Models\SectionSetting;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;

it('renders welcome page with section settings in resume payload', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('resume.settings', fn (Assert $settings) => $settings
            ->where('showAbout', true)
            ->where('showExperience', true)
            ->where('showSkills', true)
            ->where('showEducation', true)
            ->where('showProjects', true)
            ->where('showContact', true)
        )
    );
});

it('reflects updated section settings on the welcome page', function () {
    SectionSetting::current()->update([
        'show_about' => false,
        'show_projects' => false,
    ]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->where('resume.settings.showAbout', false)
        ->where('resume.settings.showProjects', false)
        ->where('resume.settings.showExperience', true)
    );
});

it('serializes project cover image, gallery, and rich content into inertia payload', function () {
    Project::create([
        'title' => ['en' => 'Interactive Canvas', 'pl' => 'Interaktywne Płótno'],
        'tagline' => ['en' => 'Drawing tool', 'pl' => 'Narzędzie do rysowania'],
        'category' => ['en' => 'Graphics', 'pl' => 'Grafika'],
        'description' => ['en' => 'A canvas app.', 'pl' => 'Aplikacja do rysowania.'],
        'content' => ['en' => '<p>Rich <strong>content</strong></p>', 'pl' => '<p>Bogata <strong>treść</strong></p>'],
        'cover_image' => 'projects/covers/demo.jpg',
        'gallery' => ['projects/gallery/shot1.jpg'],
        'featured' => true,
        'sort_order' => 1,
    ]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('resume.projects', fn (Assert $projects) => $projects
            ->where('0.title', 'Interactive Canvas')
            ->where('0.content', '<p>Rich <strong>content</strong></p>')
            ->where('0.coverImageUrl', fn ($url) => str_contains($url, 'projects/covers/demo.jpg'))
            ->where('0.galleryUrls.0', fn ($url) => str_contains($url, 'projects/gallery/shot1.jpg'))
            ->etc()
        )
    );
});

it('switches application locale and translations between en and pl', function () {
    // Act - English
    $responseEn = $this->get('/?lang=en');
    $responseEn->assertOk();
    $responseEn->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'en')
        ->where('translations.polish', 'Polish')
    );

    // Act - Polish
    $responsePl = $this->get('/?lang=pl');
    $responsePl->assertOk();
    $responsePl->assertInertia(fn (Assert $page) => $page
        ->where('locale', 'pl')
        ->where('translations.polish', 'Polski')
    );
});

it('primes cache keys on request and serves cached data', function () {
    // Arrange
    Cache::flush();

    // Act
    $this->get('/')->assertOk();

    // Assert
    expect(Cache::has(CacheKey::SectionSettings->value))->toBeTrue()
        ->and(Cache::has(CacheKey::Projects->value))->toBeTrue()
        ->and(Cache::has(CacheKey::GeneralInformation->value))->toBeTrue();
});
