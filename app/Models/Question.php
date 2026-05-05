<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'module', 'key', 'title', 'type', 
        'sender_name', 'sender_email', 'subject', 'body', 'options'
    ];

    // THIS PART IS CRITICAL
    protected $casts = [
        'options' => 'array', 
    ];
}