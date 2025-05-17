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
                                Department Management
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

                <!-- Breadcrumb -->
                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-3">
                    <a class="breadcrumb-item" href="{{ route('dashboard', ['role' => auth()->user()->role]) }}">Home</a>
                    <span class="breadcrumb-item active">Departments</span>
                </nav>

                <!-- Search Form -->
                <form action="{{ route('departments.index', ['role' => auth()->user()->role]) }}" method="GET" class="row mb-4 g-2 justify-content-center">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search by department name or code" value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Departments Table -->
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="block-title text-uppercase mb-0">Departments</h3>
                            <a href="{{ route('departments.create', ['role' => auth()->user()->role]) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-plus me-1"></i> Create Department
                            </a>
                        </div>
                    </div>
                    <div class="block-content block-content-full table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($departments as $index => $department)
                                <tr>
                                    <td>{{ $index + 1 + ($departments->currentPage() - 1) * $departments->perPage() }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->code }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('students.index', ['role' => auth()->user()->role, 'department' => $department->id]) }}" class="btn btn-sm btn-primary mb-1">
                                            Students
                                        </a>
                                        <a href="{{ route('lecturers.index', ['role' => auth()->user()->role, 'department' => $department->id]) }}" class="btn btn-sm btn-info mb-1">
                                            Lecturers
                                        </a>
                                        <a href="{{ route('courses.index', ['role' => auth()->user()->role, 'department' => $department->id]) }}" class="btn btn-sm btn-secondary mb-1">
                                            Courses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No departments found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $departments->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END Page Content -->
    </main>
</x-app-layout>
