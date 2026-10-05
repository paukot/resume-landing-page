<?php

use App\Enums\SkillIcon;
use App\Filament\Resources\SkillCategories\Pages\ManageSkillCategories;
use App\Filament\Resources\SkillCategories\SkillCategoryResource;
use App\Models\SkillCategory;
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

        $this->get(SkillCategoryResource::getUrl('index'))->assertRedirect();
    });

    it('renders the index page and table component', function () {
        $this->get(SkillCategoryResource::getUrl('index'))
            ->assertOk()
            ->assertSeeLivewire(ManageSkillCategories::class);
    });
});

describe('table', function () {
    it('displays records in the table', function () {
        $category = SkillCategory::create([
            'name' => ['en' => 'Backend', 'pl' => 'Backend'],
            'icon' => SkillIcon::cases()[0]->value,
            'skills' => ['PHP', 'Laravel', 'MySQL'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageSkillCategories::class)
            ->assertCanSeeTableRecords([$category])
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('icon')
            ->assertTableColumnExists('skills');
    });
});

describe('creation', function () {
    it('can create a new record via action', function () {
        $icon = SkillIcon::cases()[0]->value;

        Livewire::test(ManageSkillCategories::class)
            ->mountAction('create')
            ->setActionData([
                'name' => ['en' => 'Frontend', 'pl' => 'Frontend PL'],
                'icon' => $icon,
                'skills' => ['HTML', 'CSS', 'JavaScript'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified();

        $category = SkillCategory::first();

        expect($category)->not->toBeNull()
            ->and($category->getTranslation('name', 'en'))->toBe('Frontend')
            ->and($category->getTranslation('name', 'pl'))->toBe('Frontend PL')
            ->and($category->icon)->toBe($icon)
            ->and($category->skills)->toBe(['HTML', 'CSS', 'JavaScript'])
            ->and($category->sort_order)->toBe(1);
    });

    it('calculates the next sort_order correctly', function () {
        SkillCategory::create([
            'name' => ['en' => 'Old', 'pl' => 'Old'],
            'icon' => SkillIcon::cases()[0]->value,
            'sort_order' => 20,
        ]);

        Livewire::test(ManageSkillCategories::class)
            ->mountAction('create')
            ->setActionData([
                'name' => ['en' => 'New', 'pl' => 'New'],
                'icon' => SkillIcon::cases()[0]->value,
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $newCategory = SkillCategory::where('name->en', 'New')->first();
        expect($newCategory->sort_order)->toBe(21);
    });

    it('validates required fields', function () {
        Livewire::test(ManageSkillCategories::class)
            ->mountAction('create')
            ->setActionData([
                'name' => ['en' => null, 'pl' => null],
                'icon' => null,
            ])
            ->callMountedAction()
            ->assertHasActionErrors([
                'name.en' => 'required',
                'name.pl' => 'required',
                'icon' => 'required',
            ]);
    });
});

describe('editing', function () {
    it('can edit an existing record and modify JSON arrays', function () {
        $category = SkillCategory::create([
            'name' => ['en' => 'DevOps', 'pl' => 'DevOps'],
            'icon' => SkillIcon::cases()[0]->value,
            'skills' => ['Docker'],
            'sort_order' => 1,
        ]);

        $newIcon = count(SkillIcon::cases()) > 1
            ? SkillIcon::cases()[1]->value
            : SkillIcon::cases()[0]->value;

        Livewire::test(ManageSkillCategories::class)
            ->mountTableAction('edit', $category)
            ->setTableActionData([
                'name' => ['en' => 'Infrastructure', 'pl' => 'Infrastruktura'],
                'icon' => $newIcon,
                'skills' => ['Docker', 'Kubernetes', 'AWS'],
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $category->refresh();

        expect($category->getTranslation('name', 'en'))->toBe('Infrastructure')
            ->and($category->getTranslation('name', 'pl'))->toBe('Infrastruktura')
            ->and($category->icon)->toBe($newIcon)
            ->and($category->skills)->toHaveCount(3)->toContain('Kubernetes');
    });
});

describe('deletion', function () {
    it('can delete a record via table action', function () {
        $category = SkillCategory::create([
            'name' => ['en' => 'To Delete', 'pl' => 'Do usunięcia'],
            'icon' => SkillIcon::cases()[0]->value,
        ]);

        Livewire::test(ManageSkillCategories::class)
            ->assertTableActionExists('delete')
            ->mountTableAction('delete', $category)
            ->callMountedTableAction()
            ->assertNotified();

        $this->assertModelMissing($category);
    });

    it('can bulk delete records', function () {
        SkillCategory::create(['name' => ['en' => 'Cat 1', 'pl' => 'Cat 1'], 'icon' => SkillIcon::cases()[0]->value, 'sort_order' => 1]);
        SkillCategory::create(['name' => ['en' => 'Cat 2', 'pl' => 'Cat 2'], 'icon' => SkillIcon::cases()[0]->value, 'sort_order' => 2]);

        $categories = SkillCategory::all();

        Livewire::test(ManageSkillCategories::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', $categories);

        expect(SkillCategory::count())->toBe(0);
    });
});
