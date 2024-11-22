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
        // Add indexes to agents and previously_processed_agents tables
        Schema::table('agents', function (Blueprint $table) {
            $table->index('agent_id'); // Add index to agent_id
        });

        Schema::table('previously_processed_agents', function (Blueprint $table) {
            $table->index('rea_id'); // Add index to rea_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes if migration is rolled back
        Schema::table('agents', function (Blueprint $table) {
            $table->dropIndex(['agent_id']); // Remove index from agent_id
        });

        Schema::table('previously_processed_agents', function (Blueprint $table) {
            $table->dropIndex(['rea_id']); // Remove index from rea_id
        });
    }
};