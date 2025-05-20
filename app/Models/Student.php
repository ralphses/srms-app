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
        'department_id',
        'matric_no',
        'current_level',
        'next_level',
        'program_type',
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

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getSessions()
    {
        return SchoolSession::query()->whereIn('id', function ($query) {
            $query->select('school_session_id')
                ->from('course_registrations')
                ->where('student_id', $this->id);
        })->distinct()->get();
    }

    public function getCurrentSession()
    {
        return SchoolSession::query()->whereIn('id', function ($query) {
            $query->select('school_session_id')
                ->from('course_registrations')
                ->where('student_id', $this->id);
        })->latest()->first();
    }

    public function getCurrentSemester()
    {
        return SchoolSession::query()->whereIn('id', function ($query) {
            $query->select('school_session_id')
                ->from('course_registrations')
                ->where('student_id', $this->id);
        })->latest()->first()->currentSemester();
    }
}
