<x-app-layout>
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">Add New Department</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-body-extra-light py-4">
            <div class="container">

                <!-- Back Button -->
                <a href="{{ route('departments.index', ['role' => auth()->user()->role]) }}" class="btn btn-sm btn-secondary mb-3">
                    ← Back to Department List
                </a>

                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Department Information</h4>
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
                        <form action="{{ route('departments.store', ['role' => auth()->user()->role]) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                            </div>

                            <div class="mb-3">
                                <label for="code" class="form-label">Department Code</label>
                                <input type="text" class="form-control" id="code" name="code" required value="{{ old('code') }}">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4">Save Department</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-app-layout>
