<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApplication extends Model
{
    use HasFactory;
    // One to Many reverse relation
    public function job(){
        return $this->belongsTo(Job::class);
    }
}
