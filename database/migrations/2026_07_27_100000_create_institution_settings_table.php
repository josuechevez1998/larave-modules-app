<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('tagline')->nullable();
            $table->string('support_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_settings');
    }
};
