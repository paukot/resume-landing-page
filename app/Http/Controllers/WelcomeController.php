<?php

namespace App\Http\Controllers;

use App\Enums\CacheKey;
use App\Http\Resources\ResumeResource;
use App\Models\GeneralInformation;
use App\Services\WelcomeService;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function __invoke(WelcomeService $welcomeService)
    {
        return Inertia::render('Welcome', [
            'resume' => ResumeResource::make(
                $welcomeService->getCombinedData()
            )->resolve(),
        ]);
    }
}
