<?php

use App\Models\GeneralInformation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_information', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('title');
            $table->json('intro');
            $table->json('summary');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->json('cv');
            $table->timestamps();
        });

        GeneralInformation::create([
            'name' => 'Ange Doe',
            'title' => ['en' => 'Biologist', 'pl' => 'Biologist'],
            'intro' => ['en' => 'intro text', 'pl' => 'intro text'],
            'summary' => ['en' => 'summary text', 'pl' => 'summary text'],
            'cv' => ['pl' => null, 'en' => null],
            'email' => 'angnedoe@example.com',
            'phone' => '+12 123 456 789',
            'location' => 'New york',
            'linkedin' => 'https://www.linkedin.com/in/ange-doe',
            'github' => 'https://github.com/ange-doe',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('general_information');
    }
};
