<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome Yusuf Agabi</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
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
            <div class="block block-rounded block-bordered">
                <div class="block-header">
                    <h3 class="block-title text-uppercase">Course Registration - {{ $session->name }} ({{ucfirst($semester)}} Semester)</h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('student.course.register.submit', ['role' => auth()->user()->role]) }}" method="POST" id="registrationForm">
                        @csrf

                        <!-- Session and Semester (Readonly) -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Session</label>
                                <input type="text" class="form-control" value="{{ $session->name }}" readonly name="session">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Semester</label>
                                <input type="text" class="form-control" hidden value="{{$semester}}" readonly name="semester">
                                <input type="text" class="form-control" value="{{ucfirst($semester)}} semester" readonly>
                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="mb-4">
                            <label for="courseSelect" class="form-label">Select Course</label>
                            <select class="form-select" id="courseSelect">
                                <option value="" disabled selected>-- Select a course --</option>
                                @foreach($availableCourses as $course)
                                    <option value="{{ $course->id }}" data-name="{{ $course->name }}" data-id="{{ $course->id }}" data-unit="{{ $course->unit }}">
                                        {{$course->code}} - {{ $course->name }} ({{$course->unit}} Units)</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selected Courses Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="selectedCoursesTable">
                                <thead class="table-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Course Name</th>
                                    <th>Course Code</th>
                                    <th>Credit Unit</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Units:</td>
                                    <td id="totalUnits" class="fw-bold">0</td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Hidden input for selected courses -->
                        <input type="hidden" name="selected_courses" id="selectedCoursesInput">

                        <!-- Buttons -->
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('dashboard', ['role' => auth()->user()->role]) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        let selectedCourses = [];
        const courseSelect = document.getElementById('courseSelect');
        const tableBody = document.querySelector('#selectedCoursesTable tbody');
        const totalUnitsDisplay = document.getElementById('totalUnits');
        const selectedCoursesInput = document.getElementById('selectedCoursesInput');

        courseSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const id = selectedOption.dataset.id;
            const name = selectedOption.dataset.name;
            const code = selectedOption.value;
            const unit = parseInt(selectedOption.dataset.unit);

            if (!code || selectedCourses.find(c => c.code === code)) return;

            selectedCourses.push({ id, name, code, unit });  // include 'id'
            renderTable();
        });

        function removeCourse(code) {
            selectedCourses = selectedCourses.filter(c => c.code !== code);
            renderTable();
        }

        function renderTable() {
            tableBody.innerHTML = '';
            let totalUnits = 0;

            selectedCourses.forEach((course, index) => {
                totalUnits += course.unit;

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${course.name}</td>
                        <td>${course.code}</td>
                        <td>${course.unit}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeCourse('${course.code}')">
                                Remove
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            totalUnitsDisplay.textContent = totalUnits;
            selectedCoursesInput.value = JSON.stringify(selectedCourses);
        }
    </script>
</x-app-layout>
