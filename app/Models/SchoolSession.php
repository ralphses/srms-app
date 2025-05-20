<?php

namespace App\Models;

use App\Utils\Utils;
use Carbon\Carbon;
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


    public function currentSemester(): ?string
    {
        $today = now()->startOfDay();
        $secondSemesterStart = Carbon::parse($this->second_semester_start_date)->startOfDay();

        if ($today->lt($secondSemesterStart)) {
            // Today is before second semester starts
            return Utils::SEMESTER_FIRST;
        }

        // Today is on or after second semester starts
        return Utils::SEMESTER_SECOND;
    }


}
