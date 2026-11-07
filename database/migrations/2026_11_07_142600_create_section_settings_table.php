<?php

use App\Models\SectionSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_about')->default(true);
            $table->boolean('show_experience')->default(true);
            $table->boolean('show_skills')->default(true);
            $table->boolean('show_education')->default(true);
            $table->boolean('show_projects')->default(true);
            $table->boolean('show_contact')->default(true);
            $table->timestamps();
        });

        SectionSetting::create([
            'show_about' => true,
            'show_experience' => true,
            'show_skills' => true,
            'show_education' => true,
            'show_projects' => true,
            'show_contact' => true,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('section_settings');
    }
};
