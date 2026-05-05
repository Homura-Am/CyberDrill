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
        Schema::table('simulation_progress', function (Blueprint $table) {
            // Remove the old boolean
            $table->dropColumn('completed');
            // Add a text status ('correct', 'incorrect')
            $table->string('status'); 
        });
    }

    public function down(): void
    {
        Schema::table('simulation_progress', function (Blueprint $table) {
            $table->boolean('completed')->default(false);
            $table->dropColumn('status');
        });
    }
};
