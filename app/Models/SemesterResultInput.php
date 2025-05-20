<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterResultInput extends Model
{
    protected $fillable = [
        'semester_result_id',
        'course_id',
        'assignment_score',
        'test_score',
        'exam_score',
        'total_score',
        'grade',
        'grade_point',
        'remark',
    ];

    public function semesterResult() {
        return $this->belongsTo(SemesterResult::class, 'semester_result_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
