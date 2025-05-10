<x-app-layout>
    <main id="main-container">
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome Yusuf Agabi</span>
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
        <div class="content">
            <div class="block block-rounded block-bordered">
                <div class="block-header">
                    <h3 class="block-title text-uppercase">Course Registration - 2023/2024 (First Semester)</h3>
                </div>
                <div class="block-content">
                    <form action="#" method="POST" id="registrationForm">
                        @csrf

                        <!-- Course Selection -->
                        <div class="mb-4">
                            <label for="courseSelect" class="form-label">Select Course</label>
                            <select class="form-select" id="courseSelect">
                                <option value="" disabled selected>-- Select a course --</option>
                                <option value="CSC101" data-name="Introduction to Programming" data-unit="3">CSC101 - Introduction to Programming (3 Units)</option>
                                <option value="MTH101" data-name="Calculus I" data-unit="3">MTH101 - Calculus I (3 Units)</option>
                                <option value="PHY101" data-name="Physics I" data-unit="3">PHY101 - Physics I (3 Units)</option>
                                <option value="ENG101" data-name="English & Communication" data-unit="2">ENG101 - English & Communication (2 Units)</option>
                                <option value="GST101" data-name="Use of Library" data-unit="1">GST101 - Use of Library (1 Unit)</option>
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

                        <!-- Hidden input for submitting selected courses -->
                        <input type="hidden" name="selected_courses" id="selectedCoursesInput">

                        <!-- Submit Button -->
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">Submit Registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- JS to handle dynamic behavior -->
    <script>
        let selectedCourses = [];
        const courseSelect = document.getElementById('courseSelect');
        const tableBody = document.querySelector('#selectedCoursesTable tbody');
        const totalUnitsDisplay = document.getElementById('totalUnits');
        const selectedCoursesInput = document.getElementById('selectedCoursesInput');

        courseSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption.value;
            const name = selectedOption.dataset.name;
            const unit = parseInt(selectedOption.dataset.unit);

            if (!code || selectedCourses.find(c => c.code === code)) return;

            selectedCourses.push({ name, code, unit });
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
