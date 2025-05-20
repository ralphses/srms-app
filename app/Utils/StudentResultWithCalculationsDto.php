<?php

namespace App\Utils;

use AllowDynamicProperties;

#[AllowDynamicProperties] class StudentResultWithCalculationsDto
{
    public StudentResultDto $studentResult;
    public int $totalCourses;
    public int $totalUnits;
    public float $gpa;
    public float $totalGradePoints;
    public float $cgpa;
    public float $totalCumulativeGradePoints;
    public int $totalCumulativeUnits;
    public array $semesterCalculations;
    public array $sessionBreakdown; // For multi-session results

    public function __construct(
        StudentResultDto $studentResult,
        int $totalCourses,
        int $totalUnits,
        float $gpa,
        float $totalGradePoints,
        float $cgpa,
        float $totalCumulativeGradePoints,
        int $totalCumulativeUnits,
        array $semesterCalculations
    ) {
        $this->studentResult = $studentResult;
        $this->totalCourses = $totalCourses;
        $this->totalUnits = $totalUnits;
        $this->gpa = $gpa;
        $this->totalGradePoints = $totalGradePoints;
        $this->cgpa = $cgpa;
        $this->totalCumulativeGradePoints = $totalCumulativeGradePoints;
        $this->totalCumulativeUnits = $totalCumulativeUnits;
        $this->semesterCalculations = $semesterCalculations;
        $this->sessionBreakdown = []; // Initialize as empty array
    }

    /**
     * Get grade classification based on CGPA
     *
     * @return string
     */
    public function getGradeClassification(): string
    {
        if ($this->cgpa >= 3.50) {
            return 'First Class';
        } elseif ($this->cgpa >= 2.40) {
            return 'Second Class Upper';
        } elseif ($this->cgpa >= 1.50) {
            return 'Second Class Lower';
        } elseif ($this->cgpa >= 1.00) {
            return 'Third Class';
        } else {
            return 'Pass';
        }
    }

    /**
     * Check if student passed all courses
     *
     * @return bool
     */
    public function hasPassedAllCourses(): bool
    {
        foreach ($this->studentResult->semesterResults as $semesterResult) {
            foreach ($semesterResult->semesterResultInputDtos as $resultInput) {
                if ($resultInput->gradePoint < 1.0) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Get failed courses
     *
     * @return array
     */
    public function getFailedCourses(): array
    {
        $failedCourses = [];

        foreach ($this->studentResult->semesterResults as $semesterResult) {
            foreach ($semesterResult->semesterResultInputDtos as $resultInput) {
                if ($resultInput->gradePoint < 1.0) {
                    $failedCourses[] = [
                        'semester' => $semesterResult->semester,
                        'course_name' => $resultInput->courseName,
                        'course_code' => $resultInput->courseCode,
                        'grade' => $resultInput->grade,
                        'grade_point' => $resultInput->gradePoint
                    ];
                }
            }
        }

        return $failedCourses;
    }

    /**
     * Get semester with highest GPA
     *
     * @return array|null
     */
    public function getBestSemester(): ?array
    {
        if (empty($this->semesterCalculations)) {
            return null;
        }

        return collect($this->semesterCalculations)->sortByDesc('gpa')->first();
    }

    /**
     * Get semester with lowest GPA
     *
     * @return array|null
     */
    public function getWorstSemester(): ?array
    {
        if (empty($this->semesterCalculations)) {
            return null;
        }

        return collect($this->semesterCalculations)->sortBy('gpa')->first();
    }

    /**
     * Get academic status based on CGPA
     *
     * @return string
     */
    public function getAcademicStatus(): string
    {
        if ($this->cgpa >= 2.00) {
            return 'Good Standing';
        } elseif ($this->cgpa >= 1.50) {
            return 'Academic Warning';
        } else {
            return 'Academic Probation';
        }
    }

    /**
     * Get best performing session
     *
     * @return array|null
     */
    public function getBestSession(): ?array
    {
        if (empty($this->sessionBreakdown)) {
            return null;
        }

        return collect($this->sessionBreakdown)->sortByDesc('gpa')->first();
    }

    /**
     * Get worst performing session
     *
     * @return array|null
     */
    public function getWorstSession(): ?array
    {
        if (empty($this->sessionBreakdown)) {
            return null;
        }

        return collect($this->sessionBreakdown)->sortBy('gpa')->first();
    }

    /**
     * Get academic progress trend (improving/declining/stable)
     *
     * @return string
     */
    public function getAcademicTrend(): string
    {
        if (count($this->sessionBreakdown) < 2) {
            return 'Insufficient data';
        }

        $sessionGpas = array_column($this->sessionBreakdown, 'gpa');
        $firstGpa = $sessionGpas[0];
        $lastGpa = end($sessionGpas);
        $difference = $lastGpa - $firstGpa;

        if ($difference > 0.3) {
            return 'Improving';
        } elseif ($difference < -0.3) {
            return 'Declining';
        } else {
            return 'Stable';
        }
    }

    /**
     * Convert to array for easy serialization
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'student_info' => [
                'name' => $this->studentResult->studentName,
                'matric' => $this->studentResult->studentMatric,
                'department' => $this->studentResult->department,
                'level' => $this->studentResult->level,
                'program_type' => $this->studentResult->programType,
                'session' => [
                    'start' => $this->studentResult->startSession,
                    'end' => $this->studentResult->endSession,
                    'is_session' => $this->studentResult->isSession
                ]
            ],
            'academic_performance' => [
                'total_courses' => $this->totalCourses,
                'total_units' => $this->totalUnits,
                'gpa' => $this->gpa,
                'cgpa' => $this->cgpa,
                'total_grade_points' => $this->totalGradePoints,
                'grade_classification' => $this->getGradeClassification(),
                'academic_status' => $this->getAcademicStatus(),
                'passed_all_courses' => $this->hasPassedAllCourses()
            ],
            'semester_breakdown' => $this->semesterCalculations,
            'course_results' => array_map(function ($semesterResult) {
                return [
                    'semester' => $semesterResult->semester,
                    'courses' => array_map(function ($resultInput) {
                        return [
                            'course_name' => $resultInput->courseName,
                            'course_code' => $resultInput->courseCode,
                            'unit' => $resultInput->courseUnit,
                            'score' => $resultInput->score,
                            'grade' => $resultInput->grade,
                            'grade_point' => $resultInput->gradePoint,
                            'remark' => $resultInput->remark
                        ];
                    }, $semesterResult->semesterResultInputDtos)
                ];
            }, $this->studentResult->semesterResults),
            'failed_courses' => $this->getFailedCourses(),
            'best_semester' => $this->getBestSemester(),
            'worst_semester' => $this->getWorstSemester(),
            'session_breakdown' => $this->sessionBreakdown,
            'best_session' => $this->getBestSession(),
            'worst_session' => $this->getWorstSession(),
            'academic_trend' => $this->getAcademicTrend()
        ];
    }
}
