<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push align-items-center">
                    <div class="col-md">
                        <h1 class="text-white mb-0">Add New Lecturer</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-body-extra-light py-4">
            <div class="container">

                <!-- Back Button -->
                <a href="{{ route('lecturers.index', ['role' => auth()->user()->role]) }}" class="btn btn-sm btn-secondary mb-3">
                    ← Back to Lecturer List
                </a>

                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Lecturer Information</h4>
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

                        <!-- Form -->
                        <form method="POST" action="{{ route('lecturers.store', ['role' => auth()->user()->role]) }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required placeholder="Enter full name" value="{{ old('name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" required placeholder="Enter email address" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="level" class="form-label">Level</label>
                                    <select id="level" name="level" class="form-select" required>
                                        <option value="">-- Select Level --</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level }}" {{ old('level') == $level ? 'selected' : '' }}>{{ $level }} Level</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="program_type" class="form-label">Program Type</label>
                                    <select id="program_type" name="program_type" class="form-select" required>
                                        <option value="">-- Select Program Type --</option>
                                        @foreach ($programTypes as $type)
                                            <option value="{{ $type }}" {{ old('program_type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="department_id" class="form-label">Department</label>
                                <select id="department_id" name="department_id" class="form-select" required>
                                    <option value="">-- Select Department --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">Create Lecturer</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-app-layout>
