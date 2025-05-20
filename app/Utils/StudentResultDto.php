<?php

namespace App\Utils;

class StudentResultDto
{
    public string $studentName;
    public string $studentMatric;
    public string $department;
    public string $level;
    public string $programType;
    public string $startSession;
    public string $endSession;
    public bool $isSession;
    public array $semesterResults;

    public function __construct($studentName, $studentMatric, $department, $level, $programType, $startSession, $endSession, $isSession, $semesterResults) {
        $this->studentName = $studentName;
        $this->studentMatric = $studentMatric;
        $this->department = $department;
        $this->level = $level;
        $this->programType = $programType;
        $this->startSession = $startSession;
        $this->endSession = $endSession;
        $this->isSession = $isSession;
        $this->semesterResults = $semesterResults;
    }
}
