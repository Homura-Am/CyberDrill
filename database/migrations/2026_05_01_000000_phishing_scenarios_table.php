<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phishing_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'ceo_fraud', 'bank_sms'
            $table->string('title');
            $table->enum('type', ['email', 'sms']); 
            
            // Sender Details
            $table->string('sender_name');
            $table->string('sender_email')->nullable(); // Nullable because SMS doesn't use this
            
            // Content
            $table->string('subject')->nullable(); // Nullable for SMS
            $table->text('body');
            
            // Simulation Logic
            $table->boolean('is_phishing')->default(false);
            $table->string('malicious_zone')->nullable(); // e.g., 'zone1', 'zone2', 'zone3'
            $table->text('feedback')->nullable(); // Explanation shown after user acts
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phishing_scenarios');
    }
};