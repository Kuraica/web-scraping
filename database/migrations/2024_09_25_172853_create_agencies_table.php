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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('agency_url', 255)->unique();
            $table->string('full_address');
            $table->string('address')->nullable();
            $table->string('state', 50)->nullable();
            $table->string('postcode', 20)->nullable();
            $table->integer('number_of_people')->nullable();
            $table->integer('properties_sold')->nullable();
            $table->integer('properties_leased')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
