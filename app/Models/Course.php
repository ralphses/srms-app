<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'department',
    ];

    public function courseRegistrations()
    {
        return $this->belongsToMany(CourseRegistration::class, 'course_course_registration', 'course_id', 'course_registration_id');
    }

}
