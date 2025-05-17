<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push align-items-center">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">
                                Course Management
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
                    <a class="breadcrumb-item" href="{{ route('dashboard', ['role' => auth()->user()->role]) }}">Home</a>
                    <span class="breadcrumb-item active">Courses</span>
                </nav>

                <!-- Filters + Search -->
                <form id="filterForm" method="GET" action="{{ route('courses.index', ['role' => auth()->user()->role]) }}" class="row g-3 mb-4 justify-content-center">
                    <div class="col-md-3">
                        <select name="department_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="program_type" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Programs</option>
                            @foreach ($programTypes as $type)
                                <option value="{{ $type }}" {{ request('program_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="level" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Levels</option>
                            @foreach ([100, 200, 300, 400, 500] as $level)
                                <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex">
                        <input type="text" name="search" id="searchInput" class="form-control me-2" placeholder="Search by name or code" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>

                <!-- Courses Table -->
                <div class="block block-rounded block-bordered">
                    <div class="block-header d-flex justify-content-between align-items-center">
                        <h3 class="block-title text-uppercase mb-0">Courses</h3>
                        <a href="{{ route('courses.create', ['role' => auth()->user()->role]) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus me-1"></i> Create New Course
                        </a>
                    </div>

                    <div class="block-content block-content-full table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Code</th>
                                <th>Unit</th>
                                <th>Level</th>
                                <th>Semester</th>
                                <th>Program</th>
                                <th>Department</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($courses as $index => $course)
                                <tr>
                                    <td>{{ $index + 1 + ($courses->currentPage() - 1) * $courses->perPage() }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->unit }}</td>
                                    <td>{{ $course->level }}</td>
                                    <td>{{ ucfirst($course->semester) }}</td>
                                    <td>{{ ucfirst($course->program_type) }}</td>
                                    <td>{{ $course->department->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('students.index', ['role' => auth()->user()->role, 'course' => $course->id]) }}" class="btn btn-sm btn-info">
                                            View Students
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No courses found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $courses->appends(request()->query())->links() }}
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
