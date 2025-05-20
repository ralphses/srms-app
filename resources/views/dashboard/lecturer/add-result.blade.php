<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push align-items-center">
                    <div class="col-md">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75">Add Result for {{ $semesterResult->student->user->name ?? 'Unknown Student' }}</span>
                        </h1>
                    </div>
                    <div class="col-md-auto text-center text-md-end py-2">
                        <a href="{{ route('dashboard', ['role' => auth()->user()->role]) }}" class="btn btn-light">
                            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="bg-body-extra-light py-4">
            <div class="container">

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Result Entry - {{ $course->code }} ({{ $course->name }})</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('results.store', ['role' => auth()->user()->role]) }}">
                            @csrf

                            <input type="hidden" name="semester_result_id" value="{{ $semesterResult->id }}">
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="student_id" value="{{ $resultData['student_id'] }}">
                            <input type="hidden" name="semester" value="{{ $resultData['semester'] }}">
                            <input type="hidden" name="session_id" value="{{ $resultData['session_id'] }}">
                            <input type="hidden" name="level" value="{{ $resultData['level'] }}">

                            <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Matric Number</label>
                                <input type="text" name="matric_no" class="form-control" value="{{ $semesterResult->student->matric_no }}" readonly>
                            </div>
                                <div class="col-md-4 mb-3">
                                <label class="form-label">Student Name</label>
                                <input type="text" class="form-control" value="{{ $semesterResult->student->user->name }}" readonly>
                            </div>
                                <div class="col-md-4 mb-3">
                                <label class="form-label">Level</label>
                                <input type="text" class="form-control" value="{{ $semesterResult->student->current_level }}" readonly>
                            </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_assignment" class="form-label">Assignment</label>
                                    <input type="number" step="0.01" name="assignment" class="form-control" placeholder="e.g. 10" required value="{{ $semesterResultInput->assignment_score ?? 0 }}" max="20">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="test" class="form-label">Test Score</label>
                                    <input type="number" step="0.01" name="test" class="form-control" placeholder="e.g. 20" required value="{{ $semesterResultInput->test_score ?? 0 }}" max="20">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="test" class="form-label">Exam Score</label>
                                    <input type="number" step="0.01" name="exam" class="form-control" placeholder="e.g. 60" required value="{{ $semesterResultInput->exam_score ?? 0 }}" max="60">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">Submit Result</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
