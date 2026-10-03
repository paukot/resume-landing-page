<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasTranslations;

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
}
