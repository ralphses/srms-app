<?php

namespace App\Service;

use App\Models\SchoolSession;
use App\Models\SemesterResult;
use App\Models\Student;
use App\Utils\SemesterResultDto;
use App\Utils\SemesterResultInputDto;
use App\Utils\StudentResultDto;
use App\Utils\StudentResultWithCalculationsDto;

class StudentResultProcessor
{
    /**
     * Process student results and return comprehensive calculations
     *
     * @param Student $student Student model
     * @param SchoolSession|SchoolSession[] $sessions Single session or array of sessions
     * @param string|string[] $semesters Single semester or array of semesters (first/second)
     * @return StudentResultWithCalculationsDto|null
     */
    public function processStudentResults(
        Student             $student,
        array|SchoolSession $sessions,
        array|string        $semesters = ['first', 'second']
    ): ?StudentResultWithCalculationsDto
    {
        // Normalize inputs to arrays
        $sessionArray = is_array($sessions) ? $sessions : [$sessions];
        $semesterArray = is_array($semesters) ? $semesters : [$semesters];

        // Get all semester results for the given student, sessions, and semesters
        $semesterResultDtos = $this->getAllSemesterResults($student, $sessionArray, $semesterArray);

        if (empty($semesterResultDtos)) {
            return null;
        }

        // Create student result DTO with basic info
        $firstSession = $sessionArray[0];
        $studentResultDto = $this->createStudentResultDto($student, $firstSession, $semesterResultDtos);

        // Calculate semester-level statistics
        $semesterCalculations = [];
        $totalCourses = 0;
        $totalUnits = 0;
        $totalUnitsEarned = 0;
        $totalGradePoints = 0.0;

        foreach ($semesterResultDtos as $semesterResult) {
            $semesterCalculation = $this->calculateSemesterStatistics($semesterResult);
            $semesterCalculations[] = $semesterCalculation;

            // Add to totals
            $totalCourses += $semesterCalculation['courses'];
            $totalUnits += $semesterCalculation['units'];
            $totalUnitsEarned += $semesterCalculation['units_earned'];
            $totalGradePoints += $semesterCalculation['grade_points'];
        }

        // Calculate overall GPA
        $gpa = $totalUnits > 0 ? round($totalGradePoints / $totalUnits, 2) : 0.0;

        // Get previous semester results if applicable
        $previousResults = $this->getPreviousSemesterResults($student, $sessionArray, $semesterArray);

        // Calculate cumulative results (current + previous)
        $cumulativeUnits = $totalUnits;
        $cumulativeUnitsEarned = $totalUnitsEarned;
        $cumulativeGradePoints = $totalGradePoints;
        $cgpa = $gpa;

        if (!empty($previousResults)) {
            $cumulativeUnits += $previousResults['total_units'];
            $cumulativeUnitsEarned += $previousResults['total_units_earned'];
            $cumulativeGradePoints += $previousResults['total_grade_points'];
            $cgpa = $cumulativeUnits > 0 ? round($cumulativeGradePoints / $cumulativeUnits, 2) : 0.0;
        }

        // Create result with calculations
        $resultWithCalculations = new StudentResultWithCalculationsDto(
            $studentResultDto,
            $totalCourses,
            $totalUnits,
            $gpa,
            $totalGradePoints,
            $cgpa,
            $cumulativeGradePoints,
            $cumulativeUnits,
            $semesterCalculations
        );

        // Add academic summary
        $resultWithCalculations->academicSummary = [
            'current' => [
                'CUR' => $totalUnits,
                'CUE' => $totalUnitsEarned,
                'WGP' => $totalGradePoints,
                'GPA' => $gpa
            ],
            'previous' => [
                'TCUR' => $previousResults['total_units'] ?? 0,
                'TCUE' => $previousResults['total_units_earned'] ?? 0,
                'TWGP' => $previousResults['total_grade_points'] ?? 0,
                'CGPA' => $previousResults['cgpa'] ?? 0
            ],
            'cumulative' => [
                'TCUR' => $cumulativeUnits,
                'TCUE' => $cumulativeUnitsEarned,
                'TWGP' => $cumulativeGradePoints,
                'CGPA' => $cgpa
            ]
        ];

        // Add previous semester results if available
        $resultWithCalculations->previousSemesterCalculations = $previousResults['semester_calculations'] ?? [];

        // Calculate session breakdown if multiple sessions
        if (count($sessionArray) > 1) {
            $resultWithCalculations->sessionBreakdown = $this->calculateSessionBreakdown($student, $sessionArray, $semesterArray);
        }

        return $resultWithCalculations;
    }

    /**
     * Get all semester results for the student
     *
     * @param Student $student
     * @param array $sessions
     * @param array $semesters
     * @return array
     */
    private function getAllSemesterResults(Student $student, array $sessions, array $semesters): array
    {
        $allSemesterResults = [];
        foreach ($sessions as $session) {
            foreach ($semesters as $semester) {
                $semesterResult = $this->getSemesterResult($student->id, $semester, $session->id);
                if ($semesterResult) {
                    // Add academic summary for this semester result
                    $semesterStats = $this->calculateSemesterStatistics($semesterResult);

                    // Get previous results up to this semester
                    $previousResults = $this->getPreviousSemesterResultsUpTo($student, $session, $semester);

                    // Calculate cumulative stats
                    $cumulativeUnits = $semesterStats['units'] + ($previousResults['total_units'] ?? 0);
                    $cumulativeUnitsEarned = $semesterStats['units_earned'] + ($previousResults['total_units_earned'] ?? 0);
                    $cumulativeGradePoints = $semesterStats['grade_points'] + ($previousResults['total_grade_points'] ?? 0);
                    $cgpa = $cumulativeUnits > 0 ? round($cumulativeGradePoints / $cumulativeUnits, 2) : 0.0;

                    // Add academic summary to the semester result
                    $semesterResult->academicSummary = [
                        'current' => [
                            'CUR' => $semesterStats['units'],
                            'CUE' => $semesterStats['units_earned'],
                            'WGP' => $semesterStats['grade_points'],
                            'GPA' => $semesterStats['gpa']
                        ],
                        'previous' => [
                            'TCUR' => $previousResults['total_units'] ?? 0,
                            'TCUE' => $previousResults['total_units_earned'] ?? 0,
                            'TWGP' => $previousResults['total_grade_points'] ?? 0,
                            'CGPA' => $previousResults['cgpa'] ?? 0
                        ],
                        'cumulative' => [
                            'TCUR' => $cumulativeUnits,
                            'TCUE' => $cumulativeUnitsEarned,
                            'TWGP' => $cumulativeGradePoints,
                            'CGPA' => $cgpa
                        ]
                    ];

                    $allSemesterResults[] = $semesterResult;
                }
            }
        }
        return $allSemesterResults;
    }

    /**
     * Get semester result for a student
     *
     * @param int $studentId
     * @param string $semester
     * @param int $sessionId
     * @return SemesterResultDto|null
     */
    private function getSemesterResult(int $studentId, string $semester, int $sessionId): ?SemesterResultDto
    {
        // Get semester result with all related data
        $semesterResult = SemesterResult::query()
            ->where('student_id', $studentId)
            ->where('semester', $semester)
            ->where('session_id', $sessionId)
            ->with([
                'results.course',
            ])
            ->first();

        if (!$semesterResult || $semesterResult->results->isEmpty()) {
            return null;
        }

        // Create SemesterResultInputDto array
        $semesterResultInputDtos = $semesterResult->results->map(function ($result) {
            return new SemesterResultInputDto(
                $result->course->name ?? 'N/A',
                $result->course->code ?? 'N/A',
                $result->course->unit ?? 0,
                $result->total_score ?? 0,
                $result->grade ?? 'F',
                $result->grade_point ?? 0,
                $result->remark ?? ''
            );
        })->toArray();

        // Create and return SemesterResultDto
        return new SemesterResultDto(
            $semester,
            $semesterResultInputDtos
        );
    }

    /**
     * Get previous semester results up to a specific semester
     *
     * @param Student $student
     * @param SchoolSession $currentSession
     * @param string $currentSemester
     * @return array
     */
    private function getPreviousSemesterResultsUpTo(Student $student, SchoolSession $currentSession, string $currentSemester): array
    {
        $previousSemesterResults = [];
        $previousSessions = SchoolSession::query()
            ->where('name', '<', $currentSession->name)
            ->orderBy('name', 'asc')
            ->get();

        // Add all results from previous sessions
        foreach ($previousSessions as $session) {
            $semesters = ['first', 'second'];
            foreach ($semesters as $semester) {
                $semesterResult = $this->getSemesterResult($student->id, $semester, $session->id);
                if ($semesterResult) {
                    $previousSemesterResults[] = $semesterResult;
                }
            }
        }

        // Add first semester of current session if current semester is second
        if ($currentSemester === 'second') {
            $firstSemesterResult = $this->getSemesterResult($student->id, 'first', $currentSession->id);
            if ($firstSemesterResult) {
                $previousSemesterResults[] = $firstSemesterResult;
            }
        }

        // Calculate totals for previous results
        $previousTotalUnits = 0;
        $previousTotalUnitsEarned = 0;
        $previousTotalGradePoints = 0;
        $previousSemesterCalculations = [];

        foreach ($previousSemesterResults as $prevSemesterResult) {
            $semesterStats = $this->calculateSemesterStatistics($prevSemesterResult);
            $previousSemesterCalculations[] = $semesterStats;
            $previousTotalUnits += $semesterStats['units'];
            $previousTotalUnitsEarned += $semesterStats['units_earned'];
            $previousTotalGradePoints += $semesterStats['grade_points'];
        }

        $previousCgpa = $previousTotalUnits > 0 ?
            round($previousTotalGradePoints / $previousTotalUnits, 2) : 0.0;

        return [
            'total_units' => $previousTotalUnits,
            'total_units_earned' => $previousTotalUnitsEarned,
            'total_grade_points' => $previousTotalGradePoints,
            'cgpa' => $previousCgpa,
            'semester_calculations' => $previousSemesterCalculations
        ];
    }

    /**
     * Get previous semester results for the student
     *
     * @param Student $student
     * @param array $sessions
     * @param array $semesters
     * @return array
     */
    private function getPreviousSemesterResults(Student $student, array $sessions, array $semesters): array
    {
        // If requesting only one semester from one session, find previous semester results
        if (count($sessions) === 1 && count($semesters) === 1) {
            $currentSession = $sessions[0];
            $currentSemester = $semesters[0];

            // If current semester is second, get first semester of same session
            if ($currentSemester === 'second') {
                $previousSemester = $this->getSemesterResult($student->id, 'first', $currentSession->id);
                if ($previousSemester) {
                    $previousSemesterStats = $this->calculateSemesterStatistics($previousSemester);
                    return [
                        'total_units' => $previousSemesterStats['units'],
                        'total_units_earned' => $previousSemesterStats['units_earned'],
                        'total_grade_points' => $previousSemesterStats['grade_points'],
                        'cgpa' => $previousSemesterStats['gpa'],
                        'semester_calculations' => [$previousSemesterStats]
                    ];
                }
            } else {
                // If current semester is first, get previous session's results
                $previousSession = SchoolSession::query()->where('name', '<', $currentSession->name)
                    ->orderBy('name', 'desc')
                    ->first();
                if ($previousSession) {
                    $previousSemesterResults = $this->getAllSemesterResults(
                        $student,
                        [$previousSession],
                        ['first', 'second']
                    );
                    if (!empty($previousSemesterResults)) {
                        $previousSemesterCalculations = [];
                        $previousTotalUnits = 0;
                        $previousTotalUnitsEarned = 0;
                        $previousTotalGradePoints = 0;
                        foreach ($previousSemesterResults as $prevSemesterResult) {
                            $semesterStats = $this->calculateSemesterStatistics($prevSemesterResult);
                            $previousSemesterCalculations[] = $semesterStats;
                            $previousTotalUnits += $semesterStats['units'];
                            $previousTotalUnitsEarned += $semesterStats['units_earned'];
                            $previousTotalGradePoints += $semesterStats['grade_points'];
                        }
                        $previousCgpa = $previousTotalUnits > 0 ?
                            round($previousTotalGradePoints / $previousTotalUnits, 2) : 0.0;
                        return [
                            'total_units' => $previousTotalUnits,
                            'total_units_earned' => $previousTotalUnitsEarned,
                            'total_grade_points' => $previousTotalGradePoints,
                            'cgpa' => $previousCgpa,
                            'semester_calculations' => $previousSemesterCalculations
                        ];
                    }
                }
            }
        }

        // For multiple sessions/semesters or if no previous results found
        return [
            'total_units' => 0,
            'total_units_earned' => 0,
            'total_grade_points' => 0,
            'cgpa' => 0,
            'semester_calculations' => []
        ];
    }

    /**
     * Create student result DTO with basic info
     *
     * @param Student $student
     * @param SchoolSession $session
     * @param array $semesterResults
     * @return StudentResultDto
     */
    private function createStudentResultDto(Student $student, SchoolSession $session, array $semesterResults): StudentResultDto
    {
        // Load relations if not already loaded
        if (!$student->relationLoaded('user')) {
            $student->load('user');
        }
        if (!$student->relationLoaded('department')) {
            $student->load('department');
        }

        // Determine session display format
        $sessionName = $session->name ?? '';
        $sessionParts = explode('/', $sessionName);
        $startSession = $sessionParts[0] ?? $sessionName;
        $endSession = $sessionParts[1] ?? '';
        $isSession = !empty($endSession);

        // Create and return StudentResultDto
        return new StudentResultDto(
            $student->user->name ?? 'N/A',
            $student->matric_no ?? 'N/A',
            $student->department->name ?? 'N/A',
            $student->current_level ?? 'N/A',
            $student->program_type ?? 'N/A',
            $startSession,
            $endSession,
            $isSession,
            $semesterResults
        );
    }

    /**
     * Calculate statistics for a semester
     *
     * @param SemesterResultDto $semesterResult
     * @return array
     */
    private function calculateSemesterStatistics(SemesterResultDto $semesterResult): array
    {
        $semesterCourses = count($semesterResult->semesterResultInputDtos);
        $semesterUnits = 0;
        $semesterUnitsEarned = 0;
        $semesterGradePoints = 0.0;

        foreach ($semesterResult->semesterResultInputDtos as $resultInput) {
            $courseUnit = $resultInput->courseUnit;
            $semesterUnits += $courseUnit;

            // Calculate units earned (assuming passing grade has gradePoint > 0)
            if ($resultInput->gradePoint > 0) {
                $semesterUnitsEarned += $courseUnit;
            }

            $semesterGradePoints += ($resultInput->gradePoint * $courseUnit);
        }

        $semesterGpa = $semesterUnits > 0 ? round($semesterGradePoints / $semesterUnits, 2) : 0.0;

        return [
            'semester' => $semesterResult->semester,
            'courses' => $semesterCourses,
            'units' => $semesterUnits,
            'units_earned' => $semesterUnitsEarned,
            'grade_points' => $semesterGradePoints,
            'gpa' => $semesterGpa
        ];
    }

    /**
     * Calculate session-by-session breakdown of performance
     *
     * @param Student $student
     * @param array $sessions
     * @param array $semesters
     * @return array
     */
    private function calculateSessionBreakdown(Student $student, array $sessions, array $semesters): array
    {
        $sessionBreakdown = [];

        foreach ($sessions as $session) {
            $sessionResults = [];
            $sessionCourses = 0;
            $sessionUnits = 0;
            $sessionUnitsEarned = 0;
            $sessionGradePoints = 0.0;

            foreach ($semesters as $semester) {
                $semesterResult = $this->getSemesterResult($student->id, $semester, $session->id);
                if ($semesterResult) {
                    $sessionResults[] = $semesterResult;
                    $semesterStats = $this->calculateSemesterStatistics($semesterResult);
                    $sessionCourses += $semesterStats['courses'];
                    $sessionUnits += $semesterStats['units'];
                    $sessionUnitsEarned += $semesterStats['units_earned'];
                    $sessionGradePoints += $semesterStats['grade_points'];
                }
            }

            if (!empty($sessionResults)) {
                $sessionGpa = $sessionUnits > 0 ? round($sessionGradePoints / $sessionUnits, 2) : 0.0;
                $sessionBreakdown[] = [
                    'session' => $session->name,
                    'courses' => $sessionCourses,
                    'units' => $sessionUnits,
                    'units_earned' => $sessionUnitsEarned,
                    'grade_points' => $sessionGradePoints,
                    'gpa' => $sessionGpa,
                    'semesters' => array_map(function ($result) {
                        return $result->semester;
                    }, $sessionResults)
                ];
            }
        }

        return $sessionBreakdown;
    }
}
