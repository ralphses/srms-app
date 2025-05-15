<x-app-layout>
    <main id="main-container">
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">Add New Student</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-body-extra-light py-4">
            <div class="container">

                <a href="{{ route('dashboard', ['role' => auth()->user()->role]) }}" class="btn btn-sm btn-secondary mb-3">
                    ← Back to Dashboard
                </a>

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Student Information</h4>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('students.store', ['role' => auth()->user()->role]) }}" method="POST">
                            @csrf

                            <!-- User Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                                </div>
                            </div>

                            <!-- Student Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="current_level" class="form-label">Current Level</label>
                                    <select class="form-select" id="current_level" name="current_level" required>
                                        <option value="">-- Select Level --</option>
                                        @for ($i = 100; $i <= 900; $i += 100)
                                            <option value="{{ $i }}" {{ old('current_level') == $i ? 'selected' : '' }}>{{ $i }} Level</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="program_type" class="form-label">Program Type</label>
                                    <select class="form-select" id="program_type" name="program_type" required>
                                        <option value="">-- Select Program Type --</option>
                                        @foreach($programTypes as $program)
                                            <option value="{{ $program }}" {{ old('program_type') == $program ? 'selected' : '' }}>
                                                {{ ucfirst($program) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="">-- Select Department --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="school_session_id" class="form-label">Session</label>
                                    <select class="form-select" id="school_session_id" name="school_session_id" required>
                                        <option value="">-- Select Session --</option>
                                        @foreach($schoolSessions as $session)
                                            <option value="{{ $session->id }}" {{ old('school_session_id') == $session->id ? 'selected' : '' }}>
                                                {{ $session->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">Save Student</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-app-layout>
