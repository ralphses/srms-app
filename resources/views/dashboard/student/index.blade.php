<x-app-layout>

    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span
                                class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome, {{auth()->user()->name}}</span>
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
                        <a class="block block-rounded block-bordered" href="#" data-bs-toggle="modal"
                           data-bs-target="#checkResultModal">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Check Result</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">View your result by
                                        semester or year
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Course Registration -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered" href="#" data-bs-toggle="modal"
                           data-bs-target="#courseRegistrationModal">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Course Registration</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Register your courses
                                        here
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered"
                           href="{{ route('profile', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Profile</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage your account
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Course Registration Modal -->
                <div class="modal fade" id="courseRegistrationModal" tabindex="-1"
                     aria-labelledby="courseRegistrationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('course.register.create', ['role' => auth()->user()->role]) }}"
                                  method="GET">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="courseRegistrationModalLabel">Select Session &
                                        Semester</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Cancel"></button>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">Proceed to Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="checkResultModal" tabindex="-1" aria-labelledby="checkResultModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-white">
                            <form action="{{ route('results.check', ['role' => auth()->user()->role]) }}" method="GET">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="checkResultModalLabel">Check Result</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                                </div>
                                <div class="modal-body">

                                    <!-- Session Filter -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select Session Option</label>
                                        <div class="form-check">
                                            <input class="form-check-input" checked type="radio" name="session_filter" value="{{App\Utils\Utils::SELECTION_FILTER_CURRENT}}" id="sessionCurrent">
                                            <label class="form-check-label" for="sessionCurrent">Current Session</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="session_filter" value="{{ App\Utils\Utils::SELECTION_FILTER_ALL }}" id="sessionAll">
                                            <label class="form-check-label" for="sessionAll">All Sessions</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="session_filter" value="{{ App\Utils\Utils::SELECTION_FILTER_SELECTED }}" id="sessionSelected">
                                            <label class="form-check-label" for="sessionSelected">Selected Sessions</label>
                                        </div>

                                        <div id="sessionCheckboxes" class="mt-2 ms-3" style="display: none;">
                                            @foreach($sessions as $session)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sessions[]" value="{{ $session->id }}" id="session_{{ $session->id }}">
                                                    <label class="form-check-label" for="session_{{ $session->id }}">{{ $session->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Semester Filter -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select Semester Option</label>

                                        <div class="form-check">
                                            <input class="form-check-input" checked type="radio" name="semester_filter" value="{{ App\Utils\Utils::SELECTION_FILTER_CURRENT }}" id="semesterCurrent">
                                            <label class="form-check-label" for="semesterCurrent">Current Semester</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="semester_filter" value="{{ App\Utils\Utils::SELECTION_FILTER_ALL }}" id="semesterAll">
                                            <label class="form-check-label" for="semesterAll">All Semesters</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="semester_filter" value="{{ App\Utils\Utils::SELECTION_FILTER_SELECTED }}" id="semesterSelected">
                                            <label class="form-check-label" for="semesterSelected">Selected Semesters</label>
                                        </div>

                                        <div id="semesterCheckboxes" class="mt-2 ms-3" style="display: none;">
                                            @foreach($semesters as $key => $semester)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="semesters[]" value="{{ $key }}" id="semester_{{ $key }}">
                                                    <label class="form-check-label" for="semester_{{ $key }}">{{ $semester }}</label>
                                                </div>
                                            @endforeach
                                        </div>
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
            </div>
            <!-- END Content -->
        </div>
        <!-- END Page Content -->
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleCheckboxGroup = (radioName, selectedValue, checkboxContainerId) => {
                const radios = document.querySelectorAll(`input[name="${radioName}"]`);
                const checkboxesContainer = document.getElementById(checkboxContainerId);

                radios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        if (this.value === selectedValue) {
                            checkboxesContainer.style.display = 'block';
                        } else {
                            checkboxesContainer.style.display = 'none';
                            checkboxesContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                        }
                    });
                });
            };

            toggleCheckboxGroup("session_filter", "selected", "sessionCheckboxes");
            toggleCheckboxGroup("semester_filter", "selected", "semesterCheckboxes");
        });
    </script>

    <!-- END Main Container -->

</x-app-layout>
