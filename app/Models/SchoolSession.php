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


    public function currentSemester()
    {
        $today = now()->startOfDay();

        $firstSemesterStart = Carbon::parse($this->first_semester_start_date)->startOfDay();
        $secondSemesterStart = Carbon::parse($this->second_semester_start_date)->startOfDay();

        if ($today->lt($firstSemesterStart)) {
            // Before first semester starts, no semester active yet, assume first semester upcoming
            return 'first';
        }

        if ($today->between($firstSemesterStart, $secondSemesterStart->subDay())) {
            // Between first semester start and day before second semester start
            return Utils::SEMESTER_FIRST;
        }

        if ($today->gte($secondSemesterStart)) {
            // On or after second semester start
            return Utils::SEMESTER_SECOND;
        }

        // Default fallback
        return null;
    }

}
