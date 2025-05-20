<?php

namespace App\Utils;

class SemesterResultInputDto
{
    public string $courseName;
    public string $courseCode;
    public int $courseUnit;
    public int $score;
    public string $grade;
    public int $gradePoint;
    public string $remark;

    public function __construct($courseName, $courseCode, $courseUnit, $score, $grade, $gradePoint, $remark){
        $this->courseName = $courseName;
        $this->courseCode = $courseCode;
        $this->courseUnit = $courseUnit;
        $this->score = $score;
        $this->grade = $grade;
        $this->gradePoint = $gradePoint;
        $this->remark = $remark;
    }

}
