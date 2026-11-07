<?php

namespace App\Models;

use App\Enums\CacheKey;
use Database\Factories\SectionSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSetting extends Model
{
    /** @use HasFactory<SectionSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'show_about',
        'show_experience',
        'show_skills',
        'show_education',
        'show_projects',
        'show_contact',
    ];

    protected $casts = [
        'show_about' => 'boolean',
        'show_experience' => 'boolean',
        'show_skills' => 'boolean',
        'show_education' => 'boolean',
        'show_projects' => 'boolean',
        'show_contact' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::SectionSettings->forget());
        static::deleted(fn () => CacheKey::SectionSettings->forget());
    }

    /**
     * Get the singleton record or create default instance.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'show_about' => true,
            'show_experience' => true,
            'show_skills' => true,
            'show_education' => true,
            'show_projects' => true,
            'show_contact' => true,
        ]);
    }
}
