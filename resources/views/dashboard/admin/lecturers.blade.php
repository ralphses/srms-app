<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push align-items-center">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">
                                Lecturer Management
                            </span>
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
        <div class="bg-body-extra-light">
            <div class="content">

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

                <!-- Breadcrumb -->
                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-3">
                    <a class="breadcrumb-item"
                       href="{{ route('dashboard', ['role' => auth()->user()->role]) }}">Home</a>
                    <span class="breadcrumb-item active">Lecturers</span>
                </nav>

                <!-- Filters + Search -->
                <form id="filterForm" method="GET"
                      action="{{ route('lecturers.index', ['role' => auth()->user()->role]) }}"
                      class="row g-3 mb-4 justify-content-center">
                    <div class="col-md-3">
                        <select name="department_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option
                                    value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="program_type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Program Types</option>
                            @foreach ($programTypes as $type)
                                <option value="{{ $type }}" {{ request('program_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 d-flex">
                        <input type="text" name="search" id="searchInput" class="form-control me-2"
                               placeholder="Search by staff ID, name or email" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>

                <!-- Lecturers Table -->
                <div class="block block-rounded block-bordered">
                    <div class="block-header d-flex justify-content-between align-items-center">
                        <h3 class="block-title text-uppercase mb-0">Lecturers</h3>
                        <a href="{{ route('lecturers.create', ['role' => auth()->user()->role]) }}"
                           class="btn btn-success btn-sm">
                            <i class="fa fa-plus me-1"></i> Add New Lecturer
                        </a>
                    </div>

                    <div class="block-content block-content-full table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Staff ID</th>
                                <th>Level</th>
                                <th>Program Type</th>
                                <th>Department</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($lecturers as $index => $lecturer)
                                <tr>
                                    <td>{{ $index + 1 + ($lecturers->currentPage() - 1) * $lecturers->perPage() }}</td>
                                    <td>{{ $lecturer->user->name ?? 'N/A' }}</td>
                                    <td>{{ $lecturer->user->email ?? 'N/A' }}</td>
                                    <td>{{ $lecturer->staff_id }}</td>
                                    <td>{{ $lecturer->level }}</td>
                                    <td>{{ ucfirst($lecturer->program_type) }}</td>
                                    <td>{{ $lecturer->department->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('lecturers.courses', ['role' => auth()->user()->role, 'lecturer' => $lecturer->id]) }}"
                                           class="btn btn-sm btn-info mb-1">
                                            View Courses
                                        </a>

                                        @if(auth()->user()->role === App\Utils\Utils::ROLE_ADMIN)
                                            <!-- Assign Course Button -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary mb-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignCourseModal-{{ $lecturer->id }}"
                                            >
                                                Assign Course
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @if(auth()->user()->role === App\Utils\Utils::ROLE_ADMIN)
                                    <div class="modal fade" id="assignCourseModal-{{ $lecturer->id }}" tabindex="-1"
                                         aria-labelledby="assignCourseModalLabel-{{ $lecturer->id }}"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <form method="POST"
                                                  action="{{ route('lecturers.course.assign.store', ['role' => auth()->user()->role, 'lecturer' => $lecturer->id]) }}"
                                                  id="assignCourseForm-{{ $lecturer->id }}">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="assignCourseModalLabel-{{ $lecturer->id }}">
                                                            Assign Courses to
                                                            <strong>{{ $lecturer->user->name ?? 'Lecturer' }}</strong>
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p class="mb-3">Select one or more courses to assign:</p>

                                                        <div class="list-group max-vh-50 overflow-auto border rounded">
                                                            @foreach ($courses as $course)
                                                                <div
                                                                    class="list-group-item d-flex align-items-center gap-3">
                                                                    <input
                                                                        class="form-check-input flex-shrink-0"
                                                                        type="checkbox"
                                                                        name="courses[]"
                                                                        value="{{ $course->id }}"
                                                                        id="courseCheck-{{ $lecturer->id }}-{{ $course->id }}"
                                                                    >
                                                                    <label class="form-check-label flex-grow-1"
                                                                           for="courseCheck-{{ $lecturer->id }}-{{ $course->id }}">
                                                                        <strong>{{ $course->code }}</strong>
                                                                        — {{ $course->name }}
                                                                        <span class="badge bg-info text-dark ms-2">{{ $course->unit }} Units</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-check me-1"></i> Assign Selected Courses
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No lecturers found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $lecturers->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Search on Enter Key -->
    <script>
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    </script>
</x-app-layout>
