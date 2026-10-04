<?php

namespace App\Models;

use App\Enums\CacheKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class QuickStat extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['label', 'description'];

    protected $fillable = ['value', 'label', 'description', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::QuickStats->forget());
        static::deleted(fn () => CacheKey::QuickStats->forget());
    }
}
