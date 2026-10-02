<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GeneralInformation extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'summary', 'cv'];

    protected $fillable = ['name', 'title', 'summary', 'email', 'phone', 'location', 'linkedin', 'github', 'cv'];
}
