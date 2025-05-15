<?php

namespace App\Models;

use Database\Factories\LecturerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    /** @use HasFactory<LecturerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'level',
        'program_type',
        'staff_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'lecturer_course', 'lecturer_id', 'course_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
