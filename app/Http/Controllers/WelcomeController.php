<?php

namespace App\Http\Controllers;

use App\Enums\CacheKey;
use App\Http\Resources\ResumeResource;
use App\Models\GeneralInformation;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        $information = cache()->remember(
            CacheKey::GeneralInformation->value,
            now()->addDay(),
            fn () => GeneralInformation::query()->first()?->getAttributes()
        );

        return Inertia::render('Welcome', [
            'resume' => ResumeResource::make(
                (new GeneralInformation)->newFromBuilder($information)
            )->resolve(),
        ]);
    }
}
