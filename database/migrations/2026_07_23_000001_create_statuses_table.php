<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('group')->default('generic');
            $table->json('name');
            $table->string('color')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['code', 'group']);
            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
