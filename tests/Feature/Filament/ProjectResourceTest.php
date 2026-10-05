<?php

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Pages\ManageProjects;
use App\Models\Project;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

describe('access', function () {
    it('redirects guests to login page', function () {
        auth()->logout();

        $this->get(ProjectResource::getUrl('index'))->assertRedirect();
    });

    it('renders the index page and table component', function () {
        $this->get(ProjectResource::getUrl('index'))
            ->assertOk()
            ->assertSeeLivewire(ManageProjects::class);
    });
});

describe('table', function () {
    it('displays records in the table', function () {
        $project = Project::create([
            'featured' => true,
            'title' => ['en' => 'My Awesome App', 'pl' => 'Moja Super Apka'],
            'tagline' => ['en' => 'It does things', 'pl' => 'Robi rzeczy'],
            'category' => ['en' => 'Web App', 'pl' => 'Aplikacja Webowa'],
            'description' => ['en' => 'Detailed desc', 'pl' => 'Szczegóły'],
            'github_url' => 'https://github.com/ange-doe/app',
            'technologies' => ['Laravel', 'Livewire', 'Tailwind'],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageProjects::class)
            ->assertCanSeeTableRecords([$project])
            ->assertTableColumnExists('title')
            ->assertTableColumnExists('category')
            ->assertTableColumnExists('github_url')
            ->assertTableColumnExists('technologies')
            ->assertTableColumnExists('featured');
    });
});

describe('creation', function () {
    it('can create a new record via action', function () {
        Livewire::test(ManageProjects::class)
            ->mountAction('create')
            ->setActionData([
                'featured' => true,
                'live_url' => 'https://example.com',
                'github_url' => 'https://github.com/example/repo',
                'title' => ['en' => 'E-commerce Platform', 'pl' => 'Platforma E-commerce'],
                'tagline' => ['en' => 'Fast online store', 'pl' => 'Szybki sklep internetowy'],
                'category' => ['en' => 'E-commerce', 'pl' => 'E-commerce'],
                'description' => ['en' => 'Built with Stripe.', 'pl' => 'Zbudowany ze Stripe.'],
                'technologies' => ['PHP', 'MySQL', 'Stripe API'],
                'highlights' => [
                    'item1' => [
                        'en' => 'Integrated payment gateways.',
                        'pl' => 'Zintegrowano bramki płatności.',
                    ],
                ],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified();

        $project = Project::where('live_url', 'https://example.com')->first();

        expect($project)->not->toBeNull()
            ->and($project->featured)->toBeTrue()
            ->and($project->getTranslation('title', 'pl'))->toBe('Platforma E-commerce')
            ->and($project->technologies)->toBe(['PHP', 'MySQL', 'Stripe API']);

        $highlights = collect($project->highlights)->first();
        expect($highlights['en'])->toBe('Integrated payment gateways.')
            ->and($highlights['pl'])->toBe('Zintegrowano bramki płatności.');
    });

    it('calculates the next sort_order correctly', function () {
        Project::create([
            'title' => ['en' => 'Old Project', 'pl' => 'Stary Projekt'],
            'tagline' => ['en' => 'T1', 'pl' => 'T1'],
            'category' => ['en' => 'C1', 'pl' => 'C1'],
            'description' => ['en' => 'D1', 'pl' => 'D1'],
            'sort_order' => 15,
        ]);

        Livewire::test(ManageProjects::class)
            ->mountAction('create')
            ->setActionData([
                'title' => ['en' => 'New Project', 'pl' => 'Nowy Projekt'],
                'tagline' => ['en' => 'T2', 'pl' => 'T2'],
                'category' => ['en' => 'C2', 'pl' => 'C2'],
                'description' => ['en' => 'D2', 'pl' => 'D2'],
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $newProject = Project::where('title->en', 'New Project')->first();
        expect($newProject->sort_order)->toBe(16);
    });

    it('validates required fields', function () {
        Livewire::test(ManageProjects::class)
            ->mountAction('create')
            ->setActionData([
                'title' => ['en' => null, 'pl' => null],
                'tagline' => ['en' => null, 'pl' => null],
                'category' => ['en' => null, 'pl' => null],
                'description' => ['en' => null, 'pl' => null],
            ])
            ->callMountedAction()
            ->assertHasActionErrors([
                'title.en' => 'required',
                'title.pl' => 'required',
                'tagline.en' => 'required',
                'tagline.pl' => 'required',
                'category.en' => 'required',
                'category.pl' => 'required',
                'description.en' => 'required',
                'description.pl' => 'required',
            ]);
    });
});

describe('editing', function () {
    it('can edit an existing record and modify JSON arrays', function () {
        $undoFakeRepeater = Repeater::fake();
        $project = Project::create([
            'featured' => false,
            'title' => ['en' => 'Portfolio V1', 'pl' => 'Portfolio V1'],
            'tagline' => ['en' => 'My first portfolio', 'pl' => 'Moje pierwsze portfolio'],
            'category' => ['en' => 'Personal', 'pl' => 'Osobiste'],
            'description' => ['en' => 'Old site.', 'pl' => 'Stara strona.'],
            'technologies' => ['HTML', 'CSS'],
            'highlights' => [
                ['en' => 'Old design', 'pl' => 'Stary design'],
            ],
            'sort_order' => 1,
        ]);

        Livewire::test(ManageProjects::class)
            ->mountTableAction('edit', $project)
            ->setTableActionData([
                'featured' => true,
                'title' => ['en' => 'Portfolio V2', 'pl' => 'Portfolio V2'],
                'tagline' => ['en' => 'Updated portfolio', 'pl' => 'Zaktualizowane portfolio'],
                'category' => ['en' => 'Personal', 'pl' => 'Osobiste'],
                'description' => ['en' => 'New site.', 'pl' => 'Nowa strona.'],
                'technologies' => ['HTML', 'CSS', 'Alpine.js'],
                'highlights' => [
                    'item1' => ['en' => 'Old design updated', 'pl' => 'Zaktualizowany stary design'],
                    'item2' => ['en' => 'Added dark mode', 'pl' => 'Dodano tryb ciemny'],
                ],
            ])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $project->refresh();

        expect($project->featured)->toBeTrue()
            ->and($project->getTranslation('title', 'en'))->toBe('Portfolio V2')
            ->and($project->technologies)->toHaveCount(3)->toContain('Alpine.js')
            ->and($project->highlights)->toHaveCount(2);
        $undoFakeRepeater();
    });
});

describe('deletion', function () {
    it('can delete a record via table action', function () {
        $project = Project::create([
            'title' => ['en' => 'To Be Deleted', 'pl' => 'Do usunięcia'],
            'tagline' => ['en' => 'T', 'pl' => 'T'],
            'category' => ['en' => 'C', 'pl' => 'C'],
            'description' => ['en' => 'D', 'pl' => 'D'],
        ]);

        Livewire::test(ManageProjects::class)
            ->assertTableActionExists('delete')
            ->mountTableAction('delete', $project)
            ->callMountedTableAction()
            ->assertNotified();

        $this->assertModelMissing($project);
    });

    it('can bulk delete records', function () {
        Project::create(['title' => ['en' => 'P1', 'pl' => 'P1'], 'tagline' => ['en' => 'T', 'pl' => 'T'], 'category' => ['en' => 'C', 'pl' => 'C'], 'description' => ['en' => 'D', 'pl' => 'D'], 'sort_order' => 1]);
        Project::create(['title' => ['en' => 'P2', 'pl' => 'P2'], 'tagline' => ['en' => 'T', 'pl' => 'T'], 'category' => ['en' => 'C', 'pl' => 'C'], 'description' => ['en' => 'D', 'pl' => 'D'], 'sort_order' => 2]);

        $projects = Project::all();

        Livewire::test(ManageProjects::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', $projects);

        expect(Project::count())->toBe(0);
    });
});
