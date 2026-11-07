<?php

namespace App\Models;

use App\Enums\CacheKey;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string|null $cover_image
 * @property array<int, string>|null $gallery
 * @property array<int, array<string, string>>|null $highlights
 * @property array<int, string>|null $technologies
 * @property string|null $live_url
 * @property string|null $github_url
 * @property bool $featured
 * @property int $sort_order
 */
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory, HasTranslations;

    /** @var list<string> */
    public array $translatable = ['title', 'tagline', 'category', 'description', 'content'];

    protected $fillable = [
        'title',
        'tagline',
        'category',
        'description',
        'content',
        'highlights',
        'technologies',
        'live_url',
        'github_url',
        'cover_image',
        'gallery',
        'featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'highlights' => 'array',
            'technologies' => 'array',
            'gallery' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::Projects->forget());
        static::deleted(fn () => CacheKey::Projects->forget());
    }
}
