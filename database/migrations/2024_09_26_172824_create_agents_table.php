<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->uuid('agent_id')->unique(); // unique agent id from realestate.com.au
            $table->string('full_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('position')->nullable();
            $table->string('job_title')->nullable();
            $table->string('years_experience')->nullable();
            $table->string('median_price_overall')->nullable();
            $table->integer('sales_count_as_lead')->nullable();
            $table->integer('secondary_sales')->nullable();
            $table->integer('number_of_5_star_reviews')->nullable();
            $table->date('oldest_transaction_date')->nullable();
            $table->date('latest_transaction_date')->nullable();
            $table->string('top_suburb_sales')->nullable();
            $table->string('rea_link');

            // Foreign key to agencies table
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
