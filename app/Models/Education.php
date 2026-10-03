<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Education extends Model
{
    use HasTranslations;
    public array $translatable = ['institution', 'institution_url', 'degree', 'field', 'location'];

    protected $fillable = ['degree', 'field', 'institution', 'institution_url', 'location', 'period', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }
}
