<?php

namespace App\Utils;

use Illuminate\Support\Str;

class Utils
{

    const  ROLE_STUDENT = "student";
    const  ROLE_LECTURER = "lecturer";
    const ROLE_ADMIN = "admin";

    const PROGRAM_TYPE_DEGREE = "degree";
    const PROGRAM_TYPE_DIPLOMA = "diploma";

    const SEMESTER_FIRST = "first";
    const SEMESTER_SECOND = "second";
    const SEMESTER_THIRD = "third";

    const SEMESTERS = [
        self::SEMESTER_FIRST => "First",
        self::SEMESTER_SECOND => "Second",
        self::SEMESTER_THIRD => "Third",
    ];

    const LEVELS = [
        100,
        200,
        300,
        400,
        500,
        600,
        700,
        800,
        900,
    ];

    public static function generateStaffId(string $role) : string
    {
        $prefix = $role === self::ROLE_LECTURER ? "LEC" : "ADM";
        return strtoupper($prefix . substr(str_replace("-", "", Str::uuid()->toString()), 0, 8));
    }

    public static function defaultPassword()
    {
        return "123456";
    }
}
