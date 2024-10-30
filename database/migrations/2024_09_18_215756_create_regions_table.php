<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->uuid('atlasId')->unique();
            $table->string('type');
            $table->string('text');
            $table->string('name');
            $table->string('state');
            $table->string('postcode')->nullable();
            $table->boolean('scraped')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
