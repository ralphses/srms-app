<?php

namespace App\Utils;

class SemesterResultDto
{
    public string $semester;
    public array $semesterResultInputDtos;

    public function __construct(string $semester, array $semesterResultInputDtos) {
        $this->semester = $semester;
        $this->semesterResultInputDtos = $semesterResultInputDtos;
    }

}
