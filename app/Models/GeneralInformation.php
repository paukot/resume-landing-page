<?php

namespace App\Models;

use App\Enums\CacheKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GeneralInformation extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'intro', 'summary', 'cv'];

    protected $fillable = [
        'name',
        'title',
        'intro',
        'summary',
        'email',
        'phone',
        'location',
        'linkedin',
        'github',
        'cv',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::GeneralInformation->forget());
        static::deleted(fn () => CacheKey::GeneralInformation->forget());
    }
}
