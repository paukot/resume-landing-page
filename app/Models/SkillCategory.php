<?php

namespace App\Models;

use App\Enums\CacheKey;
use Database\Factories\SkillCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SkillCategory extends Model
{
    /** @use HasFactory<SkillCategoryFactory> */
    use HasFactory, HasTranslations;

    /** @var list<string> */
    public array $translatable = ['name'];

    protected $fillable = ['name', 'icon', 'skills', 'sort_order'];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::SkillCategories->forget());
        static::deleted(fn () => CacheKey::SkillCategories->forget());
    }
}
