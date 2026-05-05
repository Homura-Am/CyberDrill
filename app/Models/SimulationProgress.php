<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationProgress extends Model
{
    use HasFactory;

    // THIS IS THE MISSING PIECE
    protected $fillable = [
        'user_id',
        'module',
        'scenario_id',
        'status' // Changed from 'completed'
    ];
}