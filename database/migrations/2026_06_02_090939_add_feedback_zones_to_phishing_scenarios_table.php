<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phishing_scenarios', function (Blueprint $table) {
            // Adds the new columns after the existing 'feedback' column
            $table->text('feedback_zone1')->nullable()->after('feedback');
            $table->text('feedback_zone2')->nullable()->after('feedback_zone1');
            $table->text('feedback_zone3')->nullable()->after('feedback_zone2');
        });
    }

    public function down(): void
    {
        Schema::table('phishing_scenarios', function (Blueprint $table) {
            // Removes them if you ever need to rollback
            $table->dropColumn(['feedback_zone1', 'feedback_zone2', 'feedback_zone3']);
        });
    }
};