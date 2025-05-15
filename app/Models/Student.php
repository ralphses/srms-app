<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matric_no',
        'current_level',
        'next_level',
        'program_type',
        'department',
        'school_session_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session() {
        return $this->belongsTo(SchoolSession::class, 'school_session_id');
    }

    public function courseRegistrations() {
        return $this->hasMany(CourseRegistration::class);
    }
}
