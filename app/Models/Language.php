<?php

namespace App\Models;

use App\Enums\CacheKey;
use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    /** @use HasFactory<LanguageFactory> */
    use HasFactory, HasTranslations;

    /** @var list<string> */
    public array $translatable = ['name', 'level'];

    protected $fillable = ['name', 'level', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::Languages->forget());
        static::deleted(fn () => CacheKey::Languages->forget());
    }
}
