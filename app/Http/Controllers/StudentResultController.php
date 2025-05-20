<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterStudentResultRequest;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\SchoolSession;
use App\Models\SemesterResult;
use App\Models\SemesterResultInput;
use App\Models\Student;
use App\Service\StudentResultProcessor;
use App\Utils\Utils;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Throwable;

class StudentResultController extends Controller
{
    protected StudentResultProcessor $studentResultProcessor;

    public function __construct(StudentResultProcessor $studentResultProcessor) {
        $this->studentResultProcessor = $studentResultProcessor;
    }
    public function index(FilterStudentResultRequest $request)
    {
        $user = $request->user();
        $student = $user->profile();

        $validated = $request->validated();

        $selectedSessions = $this->resolveSessions(
            $student,
            $validated['session_filter'],
            $validated['sessions'] ?? []
        );

        $selectedSemesters = $this->resolveSemesters(
            $validated['semester_filter'],
            $validated['semesters'] ?? [],
            $student->getCurrentSession()->current_semester
        );

        // Convert to array if they are collections
        if ($selectedSessions instanceof Collection) {
            $selectedSessions = $selectedSessions->all();
        }

        if ($selectedSemesters instanceof Collection) {
            $selectedSemesters = $selectedSemesters->all();
        }

        $results = $this->studentResultProcessor->processStudentResults($student, $selectedSessions, $selectedSemesters);

        return view('dashboard.student.result', compact('results'));
    }


    public function create(Request $request)
    {
        // Validate payload
        $validated = $request->validate([
            "matric_no" => ['required', 'string', Rule::exists('students', 'matric_no')],
            "course_id" => ['required', 'string', Rule::exists('courses', 'id')],
            "semester" => ['required', 'string', Rule::in([Utils::SEMESTER_SECOND, Utils::SEMESTER_FIRST])],
            "school_session_id" => ['required', 'string', Rule::exists('school_sessions', 'id')],
        ]);

        try {
            // Find student course registration
            $courseRegistration = $this->getStudentCourseRegistration($validated);

            if (!$courseRegistration) {
                return back()->withErrors(['course_registration' => "No course registration found for this student and course."]);
            }

            // Resolve course
            $course = $courseRegistration->courses()->where('course_id', $validated["course_id"])->first();

            // Find this Semester result
            $resultData = [
                "semester" => $validated["semester"],
                "session_id" => $validated["school_session_id"],
                "level" => $courseRegistration->level,
                "student_id" => $courseRegistration->student_id,
            ];

            $semesterResult = SemesterResult::query()
                ->firstOrCreate($resultData)
                ->load(['session', 'student']);

            // Find result input if existing
            $semesterResultInput = $semesterResult->results()->where('course_id', $validated["course_id"])->first();

            return view('dashboard.lecturer.add-result', compact('semesterResult', 'resultData', 'course', 'semesterResultInput'));

        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
            return back()->withErrors(['course_registration' => "Something went wrong. Try again later."]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "matric_no" => ['required', 'string', Rule::exists('students', 'matric_no')],
            "course_id" => ['required', 'integer', Rule::exists('courses', 'id')],
            "assignment" => ['required', 'integer', 'max:20'],
            "test" => ['required', 'integer', 'max:20'],
            "exam" => ['required', 'integer', 'max:60'],
            "semester" => ['required', Rule::in([Utils::SEMESTER_FIRST, Utils::SEMESTER_SECOND])],
            "session_id" => ['required', 'integer', Rule::exists('school_sessions', 'id')],
            "level" => ['required', 'integer', Rule::in(Utils::LEVELS)],
        ]);

        try {
            DB::beginTransaction();

            // Find student
            $student = Student::query()
                ->where('matric_no', $validated['matric_no'])
                ->firstOrFail();

            // Calculate total score and grade
            $totalScore = $this->resolveTotalScore($validated["assignment"], $validated["test"], $validated["exam"]);
            $grade = $this->resolveGrade($totalScore);
            $gradePoint = $this->resolveGradePoint($grade);
            $remark = $this->resolveRemark($grade);

            // Get or create the SemesterResult record
            $semesterResult = SemesterResult::firstOrCreate([
                'student_id' => $student->id,
                'session_id' => $validated["session_id"],
                'semester' => $validated["semester"],
                'level' => $validated["level"],
            ]);

            // Save or update result input for this course
            $semesterResult->results()->updateOrCreate(
                [
                    'course_id' => $validated["course_id"],
                ],
                [
                    'assignment_score' => $validated["assignment"],
                    'test_score' => $validated["test"],
                    'exam_score' => $validated["exam"],
                    'total_score' => $totalScore,
                    'grade' => $grade,
                    'grade_point' => $gradePoint,
                    'remark' => $remark,
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'Result recorded successfully.');

        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            return back()->withErrors(['error' => 'Something went wrong. Please try again later.']);
        }
    }

    public function fetchSemesterResult(SchoolSession|array $session, string|array $semester, Student $student)
    {

        $results = (new StudentResultProcessor())->processStudentResults($student, $session, $semester);
        dd($results);


        $registrationCourse = $student->courseRegistrations()
            ->where([
                'semester' => $semester,
                'school_session_id' => $session->id
            ])->get();

        dd($courses);

        // get this semester entered result
        $semesterResult = SemesterResult::query()->where([
            'student_id' => $student->id,
            'session_id' => $session->id,
            'semester' => $semester,
        ])->first();

        // Get the result inputs
        $semesterResultInputs = $semesterResult->results()->get();

        return array($registrationCourse, $semesterResultInputs);
    }

    private function getStudentCourseRegistration(array $payload)
    {
        try {
            $student = Student::query()->where('matric_no', $payload['matric_no'])->firstOrFail();

            $registration = CourseRegistration::query()->where('student_id', $student->id)
                ->where('semester', $payload['semester'])
                ->where('school_session_id', $payload['school_session_id'])
                ->whereHas('courses', function ($query) use ($payload) {
                    $query->where('courses.id', $payload['course_id']);
                })
                ->with('courses')
                ->first();

            if (!$registration) {
                throw new ModelNotFoundException();
            }

            return $registration;
        } catch (Throwable $exception) {
            Log::error($exception);
            return false;
        }
    }


    /**
     * @throws Exception
     */
    private function resolveTotalScore(int $assignment, int $test, int $exam): int
    {
        $total = $assignment + $test + $exam;
        if ($total > 100) {
            throw new Exception("Total score exceeds maximum of 100");
        }
        return $total;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function resolveGrade(int $totalScore): string
    {
        if ($totalScore < 0 || $totalScore > 100) {
            throw new InvalidArgumentException("Total score must be between 0 and 100.");
        }
        return match (true) {
            $totalScore < 40 => "F",
            $totalScore < 45 => "D",
            $totalScore < 55 => "C",
            $totalScore < 70 => "B",
            $totalScore <= 100 => "A",
        };
    }


    private function resolveGradePoint(string $grade): int
    {
        return match ($grade) {
            "A" => 5,
            "B" => 4,
            "C" => 3,
            "D" => 2,
            "E" => 1,
            default => 0,
        };
    }

    private function resolveRemark(string $grade): string
    {
        return match (strtoupper($grade)) {
            'A' => 'Distinction',
            'B' => 'Very Good',
            'C' => 'Credit',
            'D' => 'Pass',
            'E' => 'Fair',
            'F' => 'Fail',
            default => 'Invalid Grade',
        };
    }

    public function resolveSessions(Student $student, string $filter, array $sessions = [])
    {
        return match ($filter) {
            Utils::SELECTION_FILTER_ALL => $student->getSessions(),
            Utils::SELECTION_FILTER_SELECTED => SchoolSession::query()->findMany($sessions),
            default => [$student->getCurrentSession()],
        };
    }

    public function resolveSemesters(string $filter, array $semesters = [], $currentSemester = null)
    {
        return match ($filter) {
            Utils::SELECTION_FILTER_ALL => array_keys(Utils::SEMESTERS),
            Utils::SELECTION_FILTER_SELECTED => $semesters,
            default => [$currentSemester],
        };
    }


}
