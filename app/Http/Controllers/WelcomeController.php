<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResumeResource;
use App\Models\GeneralInformation;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        $information = GeneralInformation::query()->firstOrFail();
        dd(ResumeResource::make($information)->toPrettyJson());
        return Inertia::render('Welcome', [
            'resume' => ResumeResource::make($information)->resolve(),
        ]);
    }
}
