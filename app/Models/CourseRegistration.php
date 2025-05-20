<?php

namespace App\Models;

use Database\Factories\CourseRegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    /** @use HasFactory<CourseRegistrationFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'semester',
        'level',
        'school_session_id'
    ];
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_course_registration', 'course_registration_id', 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function schoolSession()
    {
        return $this->belongsTo(SchoolSession::class, 'school_session_id');
    }
}
