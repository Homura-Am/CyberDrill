<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'type',
        'sender_name',
        'sender_email',
        'subject',
        'body',
        'is_phishing',
        'malicious_zone',
        'feedback',
    ];

    protected $casts = [
        'is_phishing' => 'boolean', // Ensures JS receives true/false, not 1/0
    ];
}