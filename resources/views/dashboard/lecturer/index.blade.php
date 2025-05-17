<x-app-layout>

    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome, {{ auth()->user()->name }}</span>
                        </h1>
                    </div>
                    <div class="col-md py-2 d-md-flex align-items-md-center justify-content-md-end text-center">
                        {{-- Optional header buttons --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- END Header -->

        <!-- Page Content -->
        <div class="bg-body-extra-light">
            <!-- Breadcrumb -->
            <div class="content">
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

                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-0">
                    <a class="breadcrumb-item" href="javascript:void(0)">Home</a>
                    <span class="breadcrumb-item active">Dashboard</span>
                </nav>
            </div>
            <!-- END Breadcrumb -->

            <!-- Content -->
            <div class="content">
                <!-- Mini Stats -->
                <div class="row">

                    <!-- Record Result Trigger -->
                    <div class="col-md-6 col-xl-3">
                        <a href="#"
                           class="block block-rounded block-bordered"
                           data-bs-toggle="modal"
                           data-bs-target="#recordResultModal"
                        >
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Record Result</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Enter student results such as tests, assignments and/or exams</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Other dashboard blocks -->
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('students.index', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Students</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">View Your Students</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('courses.index', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Courses</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">View Your Assigned Courses</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('profile', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Profile</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage your account</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END More Data -->
            </div>
            <!-- END Content -->
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->


    <!-- Record Result Modal -->
    <div class="modal fade" id="recordResultModal" tabindex="-1" aria-labelledby="recordResultModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('results.store', ['role' => auth()->user()->role]) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="recordResultModalLabel">Record Student Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Matric Number -->
                    <div class="mb-3">
                        <label for="matric_no" class="form-label">Student Matric Number</label>
                        <input type="text" name="matric_no" id="matric_no" class="form-control" placeholder="Enter student matric number" required>
                    </div>

                    <!-- Course Dropdown -->
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select name="course_id" id="course_id" class="form-select" required>
                            <option value="" disabled selected>Select a course</option>
                            @foreach ($courses ?? [] as $course)
                                <option value="{{ $course->id }}">{{ $course->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Semester Dropdown -->
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select" required>
                            <option value="" disabled selected>Select semester</option>
                            @foreach ($semesters as $key => $label)
                                <option value="{{ $key }}">{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Session Dropdown -->
                    <div class="mb-3">
                        <label for="school_session_id" class="form-label">Session</label>
                        <select name="school_session_id" id="school_session_id" class="form-select" required>
                            <option value="" disabled selected>Select session</option>
                            @foreach ($sessions ?? [] as $session)
                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Result</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
