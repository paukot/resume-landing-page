<?php

namespace App\Enums;

enum CacheKey: string
{
    case Projects = 'projects';
    case Education = 'education';
    case Experiences = 'experiences';
    case GeneralInformation = 'general_information';
    case Languages = 'languages';
    case QuickStats = 'quick_stats';
    case SkillCategories = 'skill_categories';

    public function forget(): void
    {
        cache()->forget($this->value);
    }
}
