<?php

namespace App\Utils;

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
}
