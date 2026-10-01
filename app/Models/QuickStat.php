<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class QuickStat extends Model
{
    use HasTranslations;

    public array $translatable = ['label', 'description'];

    protected $fillable = ['value', 'label', 'description', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }
}
