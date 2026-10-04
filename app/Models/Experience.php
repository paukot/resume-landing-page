<?php

namespace App\Models;

use App\Enums\CacheKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Experience extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['role', 'location', 'period', 'description'];

    protected $fillable = ['role', 'company', 'company_url', 'location', 'period', 'is_current', 'description', 'highlights', 'technologies', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'highlights' => 'array',
            'technologies' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::Experiences->forget());
        static::deleted(fn () => CacheKey::Experiences->forget());
    }
}
