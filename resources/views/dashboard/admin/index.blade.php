<x-app-layout>

    <!-- Main Container -->
    <main id="main-container">
        <!-- Header -->
        <div class="bg-primary-dark">
            <div class="content content-top">
                <div class="row push">
                    <div class="col-md py-2 d-md-flex align-items-md-center text-center">
                        <h1 class="text-white mb-0">
                            <span class="fw-normal fs-lg text-white-75 d-none d-md-inline-block">Welcome, {{auth()->user()->name}}</span>
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
        <!-- END Header -->

        <!-- Page Content -->
        <div class="bg-body-extra-light">
            <!-- Breadcrumb -->
            <div class="content">
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
                <nav class="breadcrumb push rounded-pill px-4 py-2 mb-0">
                    <a class="breadcrumb-item" href="javascript:void(0)">Home</a>
                    <span class="breadcrumb-item active">Dashboard</span>
                </nav>
            </div>
            <!-- END Breadcrumb -->

            <!-- Content -->
            <div class="content">
                <!-- Mini Stats -->
                <div class="row">

                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('students.create', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Register Student</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Register a New Student</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('students.index', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Students</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage Your Students</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('students.index', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Add Course</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Add a New Course</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-3">
                        <a class="block block-rounded block-bordered" href="{{ route('profile', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Courses</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage Your Courses</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered" href="{{ route('profile', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Lecturers</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage Your Lecturers</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered" href="{{ route('profile', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Sessions</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage Academic Sessions</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Profile -->
                    <div class="col-md-6 col-xl-4">
                        <a class="block block-rounded block-bordered" href="{{ route('profile', ['role' => auth()->user()->role]) }}">
                            <div class="block-content p-2">
                                <div class="py-4 text-center bg-body-light rounded">
                                    <div class="fs-2 fw-bold mb-0">Profile</div>
                                    <div class="fs-sm fw-semibold text-sentencecase text-muted">Manage your account</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END More Data -->
            </div>
            <!-- END Content -->
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

</x-app-layout>
