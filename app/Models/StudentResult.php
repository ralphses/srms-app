<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    /** @use HasFactory<\Database\Factories\StudentResultFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'session_id',
        'semester',
        'level'
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function session() {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }

    public function results() {
        return $this->hasMany(StudentResultInput::class);
    }
}
