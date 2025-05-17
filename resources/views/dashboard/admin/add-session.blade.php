<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push align-items-center">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">
                                Create New School Session
                            </span>
                        </h1>
                    </div>
                    <div class="col-md-auto text-center text-md-end py-2">
                        <a href="{{ route('sessions.index', ['role' => auth()->user()->role]) }}" class="btn btn-light">
                            <i class="fa fa-arrow-left me-1"></i> Back to Sessions
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Header -->

        <div class="bg-body-extra-light py-4">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">School Session Information</h4>
                    </div>
                    <div class="card-body">

                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('sessions.store', ['role' => auth()->user()->role]) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Session Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="e.g. 2023/2024"
                                    value="{{ old('name') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="first_semester_start_date" class="form-label">First Semester Start Date</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="first_semester_start_date"
                                    name="first_semester_start_date"
                                    value="{{ old('first_semester_start_date') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="second_semester_start_date" class="form-label">Second Semester Start Date</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="second_semester_start_date"
                                    name="second_semester_start_date"
                                    value="{{ old('second_semester_start_date') }}"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label for="current_semester" class="form-label">Current Semester (optional)</label>
                                <select id="current_semester" name="current_semester" class="form-select">
                                    <option value="" {{ old('current_semester') == '' ? 'selected' : '' }}>-- Select Current Semester --</option>
                                    <option value="first" {{ old('current_semester') == 'first' ? 'selected' : '' }}>First Semester</option>
                                    <option value="second" {{ old('current_semester') == 'second' ? 'selected' : '' }}>Second Semester</option>
                                </select>
                                <small class="form-text text-muted">You can leave this empty to auto-detect based on dates.</small>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">Create Session</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
