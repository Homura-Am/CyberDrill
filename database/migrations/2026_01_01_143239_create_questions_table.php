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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('module')->default('phishing'); // e.g., phishing
            $table->string('key')->unique(); // Unique ID like 'ceo_fraud'
            $table->string('title');         // e.g., "Urgent Wire Transfer"
            $table->string('type');          // 'email' or 'sms'
            
            // Scenario Details
            $table->string('sender_name');
            $table->string('sender_email')->nullable();
            $table->string('subject')->nullable(); // Nullable for SMS
            $table->text('body');
            
            // Store answers/feedback as JSON
            $table->json('options'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
