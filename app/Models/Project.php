<?php

namespace App\Models;

use App\Enums\CacheKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'tagline', 'category', 'description'];

    protected $fillable = [
        'title',
        'tagline',
        'category',
        'description',
        'highlights',
        'technologies',
        'live_url',
        'github_url',
        'featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'highlights' => 'array',
            'technologies' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::Projects->forget());
        static::deleted(fn () => CacheKey::Projects->forget());
    }
}
