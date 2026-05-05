<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'module', 'score'];
    
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // --- ADD THIS MISSING METHOD ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}