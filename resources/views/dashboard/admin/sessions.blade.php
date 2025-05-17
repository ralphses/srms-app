@php use Carbon\Carbon; @endphp
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
                                School Session Management
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

                @if (session('success'))
                    <div class="alert alert-success">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                <!-- Breadcrumb -->
                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-3">
                    <a class="breadcrumb-item"
                       href="{{ route('dashboard', ['role' => auth()->user()->role]) }}">Home</a>
                    <span class="breadcrumb-item active">School Sessions</span>
                </nav>

                <!-- Sessions Table -->
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="block-title text-uppercase mb-0">School Sessions</h3>
                            <a href="{{ route('sessions.create', ['role' => auth()->user()->role]) }}"
                               class="btn btn-success btn-sm">
                                <i class="fa fa-plus me-1"></i> Create Session
                            </a>
                        </div>
                    </div>
                    <div class="block-content block-content-full table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>First Semester Start</th>
                                <th>Second Semester Start</th>
                                <th>Current Semester</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($schoolSessions as $index => $session)
                                <tr>
                                    <td>{{ $index + 1 + ($schoolSessions->currentPage() - 1) * $schoolSessions->perPage() }}</td>
                                    <td>{{ $session->name }}</td>
                                    <td>{{ Carbon::parse($session->first_semester_start_date)->format('M d, Y') }}</td>
                                    <td>{{ Carbon::parse($session->second_semester_start_date)->format('M d, Y') }}</td>
                                    <td>{{ ucfirst($session->current_semester) ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No school sessions found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $schoolSessions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END Page Content -->
    </main>
</x-app-layout>
