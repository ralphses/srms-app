<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSession extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolSessionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'first_semester_start_date',
        'second_semester_start_date',
        'current_semester',
    ];
}
