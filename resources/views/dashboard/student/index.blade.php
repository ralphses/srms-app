<x-app-layout>

    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome, {{auth()->user()->name}}</span>
                        </h1>
                    </div>
                    <div class="col-md py-2 d-md-flex align-items-md-center justify-content-md-end text-center">
                        {{--                        <button type="button" class="btn btn-alt-primary">--}}
                        {{--                            <i class="fa fa-plus opacity-50 me-1"></i> New Account--}}
                        {{--                        </button>--}}
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
                    <!-- Check Result -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered" href="#" data-bs-toggle="modal" data-bs-target="#checkResultModal">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Check Result</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">View your result by semester or year</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Course Registration -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered" href="#" data-bs-toggle="modal" data-bs-target="#courseRegistrationModal">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Course Registration</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Register your courses here</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-4">
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

                <!-- Course Registration Modal -->
                <div class="modal fade" id="courseRegistrationModal" tabindex="-1" aria-labelledby="courseRegistrationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('course.register.create', ['role' => auth()->user()->role]) }}" method="GET">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="courseRegistrationModalLabel">Select Session & Semester</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Academic Session -->
                                    <div class="mb-3">
                                        <label for="reg_session" class="form-label">Academic Session</label>
                                        <select name="school_session" id="reg_semester" class="form-select" required>
                                            <option value="" disabled selected>Select session</option>
                                            @foreach($sessions as $session)
                                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Semester -->
                                    <div class="mb-3">
                                        <label for="reg_semester" class="form-label">Semester</label>
                                        <select name="semester" id="reg_semester" class="form-select" required>
                                            <option value="" disabled selected>Select semester</option>
                                            @foreach($semesters as $key => $semester)
                                                <option value="{{ $key }}">{{ $semester }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Proceed to Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="checkResultModal" tabindex="-1" aria-labelledby="courseRegistrationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="#" method="GET">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="checkResultModal">Select Session & Semester</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Academic Session -->
                                    <div class="mb-3">
                                        <label for="reg_session" class="form-label">Academic Session</label>
                                        <select name="semester" id="reg_semester" class="form-select" required>
                                            <option value="" disabled selected>Select session</option>
                                            @foreach($sessions as $session)
                                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Semester -->
                                    <div class="mb-3">
                                        <label for="reg_semester" class="form-label">Semester</label>
                                        <select name="semester" id="reg_semester" class="form-select" required>
                                            <option value="" disabled selected>Select semester</option>
                                            @foreach($semesters as $key => $semester)
                                                <option value="{{ $key }}">{{ $semester }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">View Result</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- END Mini Stats -->

                <!-- More Data -->
                <div class="row">
                    <!-- Semester Result -->
                    <div class="col-xl-12">
                        <div class="block block-rounded block-bordered">
                            <div class="block-header">
                                <h3 class="block-title text-uppercase">Semester Result</h3>
                            </div>
                            <div class="block-content block-content-full">
                                <table class="table table-borderless table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course Name</th>
                                        <th>Course Code</th>
                                        <th>Credit Unit</th>
                                        <th>Score</th>
                                        <th>Grade</th>
                                        <th>Remark</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Example rows -->
                                    <tr>
                                        <td>1</td>
                                        <td>Introduction to Programming</td>
                                        <td>CSC101</td>
                                        <td>3</td>
                                        <td>87</td>
                                        <td>A</td>
                                        <td>Distinction</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Mathematics I</td>
                                        <td>MTH101</td>
                                        <td>3</td>
                                        <td>73</td>
                                        <td>B</td>
                                        <td>Very Good</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Communication Skills</td>
                                        <td>ENG101</td>
                                        <td>2</td>
                                        <td>62</td>
                                        <td>C</td>
                                        <td>Credit</td>
                                    </tr>
                                    <!-- Add more rows dynamically using Blade -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END Semester Result -->

                    <!-- Result Analysis Section -->
                    <div class="col-xl-12">
                        <div class="block block-rounded block-bordered mt-4">
                            <div class="block-header">
                                <h3 class="block-title text-uppercase">Result Analysis</h3>
                            </div>
                            <div class="block-content">
                                <div class="row">
                                    <!-- CURRENT -->
                                    <div class="col-md-4">
                                        <h5>Current Semester</h5>
                                        <p><strong>CUR:</strong> 4</p>
                                        <p><strong>CUE:</strong> 11</p>
                                        <p><strong>WGP:</strong> 47.6</p>
                                        <p><strong>GPA:</strong> 4.33</p>
                                    </div>

                                    <!-- PREVIOUS -->
                                    <div class="col-md-4">
                                        <h5>Previous Semester</h5>
                                        <p><strong>TCUR:</strong> 6</p>
                                        <p><strong>TCUE:</strong> 16</p>
                                        <p><strong>TWGP:</strong> 66.8</p>
                                        <p><strong>CGPA:</strong> 4.18</p>
                                    </div>

                                    <!-- CUMULATIVE -->
                                    <div class="col-md-4">
                                        <h5>Cumulative</h5>
                                        <p><strong>TCUR:</strong> 10</p>
                                        <p><strong>TCUE:</strong> 27</p>
                                        <p><strong>TWGP:</strong> 114.4</p>
                                        <p><strong>CGPA:</strong> 4.23</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Result Analysis Section -->
                </div>

                <!-- END More Data -->
            </div>
            <!-- END Content -->
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

</x-app-layout>
