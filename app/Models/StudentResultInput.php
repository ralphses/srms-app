<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResultInput extends Model
{

    protected $fillable = [
        'student_result_id',
        'course_id',
        'assignment_score',
        'test_score',
        'exam_score',
        'total_score',
        'grade',
        'grade_point',
        'remark',
    ];

    public function studentResult() {
        return $this->belongsTo(StudentResult::class, 'student_result_id');
    }

}
