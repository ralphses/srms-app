<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit',
        'level',
        'semester',
        'program_type',
        'department_id',
    ];

    public function courseRegistrations()
    {
        return $this->belongsToMany(CourseRegistration::class, 'course_course_registration', 'course_id', 'course_registration_id');
    }

    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Lecturer::class, 'lecturer_course', 'course_id', 'lecturer_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
