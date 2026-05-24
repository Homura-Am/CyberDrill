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
        $table->string('module')->default('phishing'); 
        $table->string('key')->unique(); 
        $table->string('title');         
        $table->string('type');          
        
        // Scenario Details
        $table->string('sender_name');
        $table->string('sender_email')->nullable();
        $table->string('subject')->nullable(); 
        $table->text('body');
        
        // --- ADD THESE COLUMNS TO FIX THE ERROR ---
        $table->boolean('is_phishing')->default(false);
        $table->string('malicious_zone')->nullable();
        $table->text('feedback')->nullable(); // Good to have for the simulation feedback
        
        // Store answers/feedback as JSON (Keeping this as per your original file)
        $table->json('options')->nullable(); 
        
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
