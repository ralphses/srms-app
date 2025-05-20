<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterResult extends Model
{
    protected $fillable = [
        'student_id',
        'session_id',
        'semester',
        'level',
    ];

    public function session() {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }

    public function results() {
        return $this->hasMany(SemesterResultInput::class);
    }

    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }
}
