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
        Schema::table('agencies', function (Blueprint $table) {
            $table->string('agency_website', 255)->nullable()->after('agency_url');
            $table->string('processed_by')->nullable()->after('properties_leased');
            $table->boolean('scraped')->default(0)->after('processed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('agency_website');
            $table->dropColumn('processed_by');
            $table->dropColumn('scraped');
        });
    }
};