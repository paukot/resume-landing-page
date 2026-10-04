<?php

namespace App\Models;

use App\Enums\CacheKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Education extends Model
{
    use HasFactory, HasTranslations;
    public array $translatable = ['institution', 'institution_url', 'degree', 'field', 'location'];

    protected $fillable = ['degree', 'field', 'institution', 'institution_url', 'location', 'period', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CacheKey::Education->forget());
        static::deleted(fn () => CacheKey::Education->forget());
    }
}
