<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviouslyProcessedAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previously_processed_agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rea_id')->unique();
            $table->string('candidate_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile')->nullable();
            $table->string('agency')->nullable();
            $table->string('agency_suburb')->nullable();
            $table->string('state')->nullable();
            $table->text('rea_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('previously_processed_agents');
    }
}