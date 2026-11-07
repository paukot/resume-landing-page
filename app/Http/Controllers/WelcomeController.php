<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResumeResource;
use App\Services\WelcomeService;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __invoke(WelcomeService $welcomeService): Response
    {
        return Inertia::render('Welcome', [
            'resume' => ResumeResource::make(
                $welcomeService->getCombinedData()
            )->resolve(),
        ]);
    }
}
