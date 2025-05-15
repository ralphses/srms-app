<x-app-layout>
    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push align-items-center">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">
                                Student Management
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
        <!-- END Header -->

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
                    <span class="breadcrumb-item active">Students</span>
                </nav>

                <!-- Filters + Search -->
                <form id="filterForm" method="GET" action="{{ route('students.index', ['role' => auth()->user()->role]) }}" class="row g-3 mb-4 justify-content-center">
                    <div class="col-md-3">
                        <select name="department" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="program_type" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Program Types</option>
                            @foreach ($programTypes as $type)
                                <option value="{{ $type }}" {{ request('program_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="school_session_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Sessions</option>
                            @foreach ($schoolSessions as $session)
                                <option value="{{ $session->id }}" {{ request('school_session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 d-flex">
                        <input type="text" name="search" id="searchInput" class="form-control me-2" placeholder="Search by name, matric no, or email" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>

                <!-- Students Table -->
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Students</h3>
                    </div>
                    <div class="block-content block-content-full table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Matric No</th>
                                <th>Current Level</th>
                                <th>Program Type</th>
                                <th>Department</th>
                                <th>Session</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $student->user->email ?? 'N/A' }}</td>
                                    <td>{{ $student->matric_no }}</td>
                                    <td>{{ $student->current_level }}</td>
                                    <td>{{ $student->program_type }}</td>
                                    <td>{{ $student->department->name }}</td>
                                    <td>{{ $student->session->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No students found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- END Page Content -->
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
